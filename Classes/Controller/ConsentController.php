<?php

namespace Websedit\WeCookieConsent\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Websedit\WeCookieConsent\Domain\Repository\ServiceRepository;

/***
 *
 * This file is part of the "we_cookie_consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 websedit AG <extensions@websedit.de>
 *
 ***/
class ConsentController extends ActionController
{
    const EXTKEY = 'we_cookie_consent';

    /**
     * serviceRepository
     *
     * @var ServiceRepository
     */
    protected $serviceRepository = null;

    /**
     * @var bool
     */
    private bool $assetsInjected = false;

    /**
     * Inject a service repository
     *
     * @param ServiceRepository $serviceRepository
     */
    public function injectServiceRepository(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Normalize Extbase plugin settings and merge optional Site Settings.
     *
     * Important for Site Sets:
     * Extbase does NOT evaluate TypoScript stdWrap in plugin settings, so values can arrive as arrays.
     * Also, we want to support per-site configuration via Site Settings (TYPO3 v12/v13).
     */
    public function initializeAction(): void
    {
        if (isset($this->settings['klaro']) && is_array($this->settings['klaro'])) {
            // 1) Resolve stdWrap arrays (in case projects still map via TS .data/.ifEmpty)
            $this->settings['klaro'] = $this->resolveStdWrapSettingsArray($this->settings['klaro']);
        }

        // 2) Merge Site Settings overrides (works in TYPO3 v12+)
        $site = $this->request->getAttribute('site');
        if ($site instanceof Site) {
            $this->applySiteSettingsOverrides($site);
        }

        // 3) Ensure sane defaults to avoid JS/template breakage
        if (isset($this->settings['klaro']) && is_array($this->settings['klaro'])) {
            $klaro =& $this->settings['klaro'];

            $klaro['cookieExpiresAfterDays'] = $this->toString($klaro['cookieExpiresAfterDays'] ?? '') ?: '365';
            $klaro['storageMethod'] = $this->toString($klaro['storageMethod'] ?? '') ?: 'cookie';
            $klaro['storageName'] = $this->toString($klaro['storageName'] ?? '') ?: 'klaro';
            $klaro['elementID'] = $this->toString($klaro['elementID'] ?? '') ?: 'klaro';
            $klaro['cookieIconPermanentlyAvailable'] = $this->toString($klaro['cookieIconPermanentlyAvailable'] ?? '') ?: '0';
        }
    }

    /**
     * Resolve a flat settings array where values may be stdWrap configuration arrays. where values may be stdWrap configuration arrays.
     *
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    private function resolveStdWrapSettingsArray(array $settings): array
    {
        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                // Evaluate stdWrap config array to a scalar string
                $settings[$key] = (string)$cObj->stdWrap('', $value);
            }
        }

        return $settings;
    }

    /**
     * Apply per-site overrides from Site Settings.
     *
     * This supports both "flat" keys (containing dots) and nested YAML maps.
     */
    private function applySiteSettingsOverrides(Site $site): void
    {
        $siteSettings = $site->getSettings(); // SiteSettings (TYPO3 12/13)

        // Enabled toggle (if explicitly set)
        if ($this->siteSettingIsExplicitlySet($site, 'websedit.we_cookie_consent.enabled')) {
            $this->settings['enabled'] = $siteSettings->get('websedit.we_cookie_consent.enabled');
        }

        // storagePid (nur wenn explizit gesetzt UND > 0)
        if ($this->siteSettingIsExplicitlySet($site, 'websedit.we_cookie_consent.storagePid')) {
            $storagePid = (int)$siteSettings->get('websedit.we_cookie_consent.storagePid');
            if ($storagePid > 0) {
                $this->settings['persistence']['storagePid'] = (string)$storagePid;
                $this->settings['storagePid'] = (string)$storagePid;
            } else {
                // explizit 0 => NICHT auf pid 0 zwingen (würde Services killen)
                unset($this->settings['persistence']['storagePid'], $this->settings['storagePid']);
            }
        }

        // privacyPolicyPid -> klaro.privacyPolicy
        if ($this->siteSettingIsExplicitlySet($site, 'websedit.we_cookie_consent.privacyPolicyPid')) {
            $privacyPid = (int)$siteSettings->get('websedit.we_cookie_consent.privacyPolicyPid');
            if ($privacyPid > 0) {
                $this->settings['klaro']['privacyPolicy'] = (string)$privacyPid;
            }
        }

        // Klaro subtree
        $klaroMap = [
            'cookieDomain' => 'websedit.we_cookie_consent.klaro.cookieDomain',
            'lang' => 'websedit.we_cookie_consent.klaro.lang',
            'testing' => 'websedit.we_cookie_consent.klaro.testing',
            'stylePrefix' => 'websedit.we_cookie_consent.klaro.stylePrefix',
            'additionalClass' => 'websedit.we_cookie_consent.klaro.additionalClass',
            'elementID' => 'websedit.we_cookie_consent.klaro.elementID',
            'mustConsent' => 'websedit.we_cookie_consent.klaro.mustConsent',
            'groupByPurpose' => 'websedit.we_cookie_consent.klaro.groupByPurpose',
            'acceptAll' => 'websedit.we_cookie_consent.klaro.acceptAll',
            'hideDeclineAll' => 'websedit.we_cookie_consent.klaro.hideDeclineAll',
            'hideLearnMore' => 'websedit.we_cookie_consent.klaro.hideLearnMore',
            'default' => 'websedit.we_cookie_consent.klaro.default',
            'cookieExpiresAfterDays' => 'websedit.we_cookie_consent.klaro.cookieExpiresAfterDays',
            'storageMethod' => 'websedit.we_cookie_consent.klaro.storageMethod',
            'storageName' => 'websedit.we_cookie_consent.klaro.storageName',
            'consentMode' => 'websedit.we_cookie_consent.klaro.consentMode',
            'consentModev2' => 'websedit.we_cookie_consent.klaro.consentModev2',
            'cookieSettingsImgPathDefault' => 'websedit.we_cookie_consent.klaro.cookieSettingsImgPathDefault',
            'cookieSettingsImgPathHover' => 'websedit.we_cookie_consent.klaro.cookieSettingsImgPathHover',
            'cookieIconPermanentlyAvailable' => 'websedit.we_cookie_consent.klaro.cookieIconPermanentlyAvailable',
        ];

        // --- Special handling: poweredBy ---
        $poweredByKey = 'websedit.we_cookie_consent.klaro.poweredBy';
        if ($this->siteSettingIsExplicitlySet($site, $poweredByKey)) {
            // explizit gesetzt (auch leer) -> respektieren
            $this->settings['klaro']['poweredBy'] = $siteSettings->get($poweredByKey);
        } else {
            // nicht explizit gesetzt -> Default erzwingen, falls aktuell leer
            $current = trim($this->toString($this->settings['klaro']['poweredBy'] ?? ''));
            if ($current === '') {
                // Default aus euren Definitions
                $this->settings['klaro']['poweredBy'] = 'https://consent.websedit.de';
            }
        }

        foreach ($klaroMap as $klaroKey => $path) {
            $value = $siteSettings->get($path);
            if ($value !== null) {
                $this->settings['klaro'][$klaroKey] = $value;
            }
        }
    }

    /**
     * Convert mixed values from TypoScript / Site Settings to a boolean.
     *
     * Accepts: true/false, 1/0, "1"/"0", "true"/"false", "yes"/"no", "on"/"off".
     */
    private function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return (int)$value === 1;
        }

        $str = strtolower(trim((string)$value));
        if ($str === '') {
            return false;
        }
        if (in_array($str, ['1', 'true', 'yes', 'on'], true)) {
            return true;
        }
        if (in_array($str, ['0', 'false', 'no', 'off'], true)) {
            return false;
        }

        // Fallback: non-empty string treated as true
        return true;
    }

    /**
     * Convert mixed values to an integer with a default.
     */
    private function toInt(mixed $value, int $default = 0): int
    {
        if ($value === null) {
            return $default;
        }
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return (int)$value;
        }
        $str = trim((string)$value);
        if ($str === '' || !is_numeric($str)) {
            return $default;
        }
        return (int)$str;
    }

    /**
     * Convert mixed values to a string.
     */
    private function toString(mixed $value): string
    {
        if (is_array($value)) {
            return '';
        }
        return (string)$value;
    }

    /**
     * Generate JSON data for the consent Modal
     *
     * @return ResponseInterface
     */
    public function consentAction(): ResponseInterface
    {
        // Optional master toggle via Site Settings (defaults to enabled)
        if (array_key_exists('enabled', $this->settings) && !$this->toBool($this->settings['enabled'])) {
            return new HtmlResponse('');
        }

        // Ensure all required JS/CSS snippets are injected (Klaro config, service definitions, etc.)
        $this->renderAssetsForRequest($this->request);

        return $this->htmlResponse();
    }

    /**
     * Show used cookies at the data privacy page
     *
     * @return ResponseInterface
     */
    public function listAction()
    {
        $servicesUids = explode(',', $this->settings['flexforms']['services']);

        $services = [];
        foreach ($servicesUids as $uid) {
            $services[] = $this->serviceRepository->findByUid($uid);
        }

        $this->view->assignMultiple([
            'services' => $services
        ]);

        // The list view may be used on privacy pages where consent assets are still required
        // (e.g. cookie icon / open settings). Inject them as well.
        if (!array_key_exists('enabled', $this->settings) || $this->toBool($this->settings['enabled'])) {
            $this->renderAssetsForRequest($this->request);
        }

        return $this->htmlResponse();
    }

    /**
     * @param RequestInterface $request
     */
    protected function renderAssetsForRequest($request): void
    {
        // Prevent duplicate injection within the same HTTP request
        if ($this->assetsInjected) {
            return;
        }
        $this->assetsInjected = true;

        if (!method_exists($this->view, 'renderSection')) {
            throw new \RuntimeException('The view does not support rendering sections.', 1678972450);
        }

        $services = $this->serviceRepository->findAll();
        $klaroConfig = $this->klaroConfigBuild($services);

        $variables = [
            'request' => $request,
            'arguments' => $this->arguments,
            'services' => $services,
            'klaroConfig' => $klaroConfig,
        ];

        $headerAssets = $this->view->renderSection('HeaderAssets', $variables, true);
        $footerAssets = $this->view->renderSection('FooterAssets', $variables, true);

        if (!empty(trim($headerAssets))) {
            $this->addAssetsToPageRenderer('header', $headerAssets);
        }
        if (!empty(trim($footerAssets))) {
            $this->addAssetsToPageRenderer('footer', $footerAssets);
        }
    }

    protected function addAssetsToPageRenderer(string $position, string $assets): void
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        /* @var $pageRenderer PageRenderer */

        if ($position === 'header') {
            $pageRenderer->addHeaderData($assets);
        } elseif ($position === 'footer') {
            $pageRenderer->addFooterData($assets);
        }
    }

    /**
     * Build the klaro config object used in frontend
     *
     * @param QueryResult $services
     * @return array
     */
    private function klaroConfigBuild(QueryResult $services)
    {
        $privacyPolicySetting = $this->toString($this->settings['klaro']['privacyPolicy'] ?? '');
        if (is_numeric($privacyPolicySetting)) {
            $privacyPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int)$privacyPolicySetting)
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $privacyPage = $privacyPolicySetting;
        }

        $poweredBySetting = $this->toString($this->settings['klaro']['poweredBy'] ?? '');
        if (is_numeric($poweredBySetting)) {
            $poweredByPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int)$poweredBySetting)
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $poweredByPage = $poweredBySetting;
        }

        // Disable mustConsent on the privacy policy page to keep it readable
        $privacyPolicySetting = $this->toString($this->settings['klaro']['privacyPolicy'] ?? '');
        $privacyPid = is_numeric($privacyPolicySetting) ? (int)$privacyPolicySetting : 0;

        $currentPid = 0;
        $pageArguments = $this->request->getAttribute('routing');
        if (is_object($pageArguments) && method_exists($pageArguments, 'getPageId')) {
            $currentPid = (int)$pageArguments->getPageId(); // TYPO3 12/13
        }

        $mustConsent = $this->toBool($this->settings['klaro']['mustConsent'] ?? false);
        if ($privacyPid > 0 && $currentPid > 0 && $privacyPid === $currentPid) {
            $mustConsent = false;
        }

        $klaroConfig = [
            'acceptAll' => $this->toBool($this->settings['klaro']['acceptAll'] ?? false),
            'additionalClass' => $this->toString($this->settings['klaro']['additionalClass'] ?? ''),
            'cookieDomain' => trim($this->toString($this->settings['klaro']['cookieDomain'] ?? '')),
            'cookieExpiresAfterDays' => $this->toInt($this->settings['klaro']['cookieExpiresAfterDays'] ?? null, 365),
            'default' => $this->toBool($this->settings['klaro']['default'] ?? false),
            'elementID' => $this->toString($this->settings['klaro']['elementID'] ?? ''),
            'groupByPurpose' => $this->toBool($this->settings['klaro']['groupByPurpose'] ?? false),
            'hideDeclineAll' => $this->toBool($this->settings['klaro']['hideDeclineAll'] ?? false),
            'hideLearnMore' => $this->toBool($this->settings['klaro']['hideLearnMore'] ?? false),
            'htmlTexts' => true,
            'lang' => 'en', //Don't change this, else locallang translation didn't work
            'mustConsent' => $mustConsent,
            'poweredBy' => $poweredByPage,
            'privacyPolicy' => $privacyPage,
            'storageMethod' => $this->toString($this->settings['klaro']['storageMethod'] ?? 'cookie') ?: 'cookie',
            'storageName' => $this->toString($this->settings['klaro']['storageName'] ?? 'klaro') ?: 'klaro',
            'stylePrefix' => $this->toString($this->settings['klaro']['stylePrefix'] ?? ''),
            'testing' => $this->toBool($this->settings['klaro']['testing'] ?? false),
            'consentMode' => $this->toBool($this->settings['klaro']['consentMode'] ?? false),
            'consentModev2' => $this->toBool($this->settings['klaro']['consentModev2'] ?? false),
            'translations' => [
                'en' => [
                    'consentModal' => [
                        'title' => LocalizationUtility::translate('klaro.consentModal.title', self::EXTKEY),
                        'description' => LocalizationUtility::translate('klaro.consentModal.description', self::EXTKEY)
                    ],
                    'privacyPolicy' => [
                        'text' => LocalizationUtility::translate('klaro.consentModal.privacyPolicy.text', self::EXTKEY),
                        'name' => LocalizationUtility::translate('klaro.consentModal.privacyPolicy.name', self::EXTKEY)
                    ],
                    'consentNotice' => [
                        'description' => LocalizationUtility::translate('klaro.consentNotice.description', self::EXTKEY, [$privacyPage]),
                        'changeDescription' => LocalizationUtility::translate('klaro.consentNotice.changeDescription', self::EXTKEY),
                        'learnMore' => LocalizationUtility::translate('klaro.consentNotice.learnMore', self::EXTKEY)
                    ],
                    'contextualConsent' => [
                        'acceptOnce' => LocalizationUtility::translate('klaro.contextualConsent.acceptOnce', self::EXTKEY),
                        'acceptAlways' => LocalizationUtility::translate('klaro.contextualConsent.acceptAlways', self::EXTKEY),
                        'description' => LocalizationUtility::translate('klaro.contextualConsent.description', self::EXTKEY),
                    ],
                    'service' => [
                        'disableAll' => [
                            'title' => LocalizationUtility::translate('klaro.service.disableAll.title', self::EXTKEY),
                            'description' => LocalizationUtility::translate('klaro.service.disableAll.description', self::EXTKEY)
                        ],
                        'optOut' => [
                            'title' => LocalizationUtility::translate('klaro.service.optOut.title', self::EXTKEY),
                            'description' => LocalizationUtility::translate('klaro.service.optOut.description', self::EXTKEY)
                        ],
                        'required' => [
                            'title' => LocalizationUtility::translate('klaro.service.required.title', self::EXTKEY),
                            'description' => LocalizationUtility::translate('klaro.service.required.description', self::EXTKEY)
                        ],
                        'purpose' => LocalizationUtility::translate('klaro.service.purpose', self::EXTKEY),
                        'purposes' => LocalizationUtility::translate('klaro.service.purposes', self::EXTKEY)
                    ],
                    'purposes' => [
                        'unknown' => LocalizationUtility::translate('klaro.purposes.unknown', self::EXTKEY)
                    ],
                    'ok' => LocalizationUtility::translate('klaro.ok', self::EXTKEY),
                    'save' => LocalizationUtility::translate('klaro.save', self::EXTKEY),
                    'acceptAll' => LocalizationUtility::translate('klaro.acceptAll', self::EXTKEY),
                    'acceptSelected' => LocalizationUtility::translate('klaro.acceptSelected', self::EXTKEY),
                    'decline' => LocalizationUtility::translate('klaro.decline', self::EXTKEY),
                    'close' => LocalizationUtility::translate('klaro.close', self::EXTKEY),
                    'openConsent' => LocalizationUtility::translate('list.button.openConsent', self::EXTKEY),
                    'poweredBy' => LocalizationUtility::translate('klaro.poweredBy', self::EXTKEY) ?: ' '
                ]
            ],
            'services' => []
            /* Prepared for later use
            'embedded' => $this->settings['klaro']['embedded'] === '1',,
            'hideToggleAll' => $this->settings['klaro']['hideToggleAll'] === '1',
            'noAutoLoad' => $this->settings['klaro']['noAutoLoad'] === '1',,
            'noticeAsModal' => $this->settings['klaro']['noticeAsModal'] === '1',
            'styling' => ['theme' => ['light', 'bottom', 'wide']],
            */
        ];

        foreach ($services as $service) {
            if ($service->getCategories()->count()) {
                foreach ($service->getCategories() as $category) {
                    $klaroConfig['translations']['en']['purposes'][mb_strtolower($category->getTitle(), 'UTF-8')]['title'] = $category->getTitle();
                    $klaroConfig['translations']['en']['purposes'][mb_strtolower($category->getTitle(), 'UTF-8')]['description'] = $category->getDescription();

                    // Sorting the sys_categories
                    $klaroConfig['purposeOrder'][$this->serviceRepository->getCategorySortingByUid($category->getUid())] = mb_strtolower($category->getTitle(), 'UTF-8');
                }
            }
        }

        if (array_key_exists('purposeOrder', $klaroConfig) && is_array($klaroConfig['purposeOrder'])) {
            // Sort the sys_categories alphabetically and add a last category 'unknown' for uncategorized services.
            // Only relevant if option 'groupByPurpose' is set to true.
            ksort($klaroConfig['purposeOrder']);
            $result = array_values($klaroConfig['purposeOrder']);
            $klaroConfig['purposeOrder'] = $result;
            $klaroConfig['purposeOrder'][] = 'unknown';
        }

        return $klaroConfig;
    }

    private function siteSettingIsExplicitlySet(Site $site, string $path): bool
    {
        $settings = $site->getConfiguration()['settings'] ?? [];

        // Flat key?
        if (is_array($settings) && array_key_exists($path, $settings)) {
            return true;
        }

        // Nested map?
        $segments = explode('.', $path);
        $current = $settings;
        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return false;
            }
            $current = $current[$segment];
        }
        return true;
    }
}
