<?php

namespace Websedit\WeCookieConsent\Controller;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/***
 *
 * This file is part of the "we_cookie_consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Erwin Steinbinder <extensions@websedit.de>, websedit AG
 *
 ***/

/**
 * ConsentController
 */
class ConsentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    const EXTKEY = 'we_cookie_consent';

    /**
     * serviceRepository
     *
     * @var \Websedit\WeCookieConsent\Domain\Repository\ServiceRepository
     */
    protected $serviceRepository = null;

    /**
     * Inject a service repository
     *
     * @param \Websedit\WeCookieConsent\Domain\Repository\ServiceRepository $serviceRepository
     */
    public function injectServiceRepository(\Websedit\WeCookieConsent\Domain\Repository\ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Generate JSON data for the consent Modal
     *
     * @param Websedit\WeCookieConsent\Domain\Model\Service
     * @return void
     */
    public function consentAction()
    {
        $services = $this->serviceRepository->findAll();

        // These two lines are only required for TYPO3 7 backwards compatibility. in TYPO3 >=8 renderAssetsForRequest is used
        $klaroConfig = $this->klaroConfigBuild($services);
        $typo3Version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);

        $this->view->assignMultiple([
            'services' => $services,
            'klaroConfig' => $klaroConfig,
            'typo3Version' => $typo3Version
        ]);
    }

    /**
     * Show used cookies at the data privacy page
     *
     * @param Websedit\WeCookieConsent\Domain\Model\Service
     * @return void
     */
    public function listAction()
    {
        $servicesUids = explode(',', $this->settings['flexforms']['services']);

        $services = [];
        foreach ($servicesUids as $uid) {
            // No custom findByUids function to keep the sorting
            $services[] = $this->serviceRepository->findByUid($uid);
        }

        $this->view->assignMultiple([
            'services' => $services
        ]);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     */
    protected function renderAssetsForRequest($request)
    {
        if (!$this->view instanceof \TYPO3Fluid\Fluid\View\TemplateView) {
            return;
        }

        $services = $this->serviceRepository->findAll();
        $klaroConfig = $this->klaroConfigBuild($services);

        $pageRenderer = $this->objectManager->get(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $variables = [
            'request' => $request,
            'arguments' => $this->arguments,
            'services' => $services,
            'klaroConfig' => $klaroConfig
        ];

        $headerAssets = $this->view->renderSection('HeaderAssets', $variables, true);
        $footerAssets = $this->view->renderSection('FooterAssets', $variables, true);

        if (!empty(trim($headerAssets))) {
            $pageRenderer->addHeaderData($headerAssets);
        }
        if (!empty(trim($footerAssets))) {
            $pageRenderer->addFooterData($footerAssets);
        }
    }

    /**
     * Build the klaro config object used in frontend
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $services
     * @return array
     */
    private function klaroConfigBuild(\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $services)
    {
        if (\is_numeric($this->settings['klaro']['privacyPolicy'])) {
            $privacyPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int)$this->settings['klaro']['privacyPolicy'])
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $privacyPage = $this->settings['klaro']['privacyPolicy'];
        }

        if (\is_numeric($this->settings['klaro']['poweredBy'])) {
            $poweredByPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int)$this->settings['klaro']['poweredBy'])
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $poweredByPage = $this->settings['klaro']['poweredBy'];
        }

        $klaroConfig = [
            'acceptAll' => $this->settings['klaro']['acceptAll'] === '1',
            'additionalClass' => $this->settings['klaro']['additionalClass'],
            'cookieDomain' => trim($this->settings['klaro']['cookieDomain']),
            'cookieExpiresAfterDays' => $this->settings['klaro']['cookieExpiresAfterDays'],
            'default' => $this->settings['klaro']['default'] === '1',
            'elementID' => $this->settings['klaro']['elementID'],
            'groupByPurpose' => $this->settings['klaro']['groupByPurpose'] === '1',
            'hideDeclineAll' => $this->settings['klaro']['hideDeclineAll'] === '1',
            'hideLearnMore' => $this->settings['klaro']['hideLearnMore'] === '1',
            'htmlTexts' => true,
            'lang' => 'en', //Don't change this, else locallang translation didn't work
            'mustConsent' => $this->settings['klaro']['mustConsent'] === '1',
            'poweredBy' => $poweredByPage,
            'privacyPolicy' => $privacyPage,
            'storageMethod' => $this->settings['klaro']['storageMethod'],
            'storageName' => $this->settings['klaro']['storageName'],
            'stylePrefix' => $this->settings['klaro']['stylePrefix'],
            'testing' => $this->settings['klaro']['testing'] === '1',
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
            foreach ($service->getCategories() as $category) {
                $klaroConfig['translations']['en']['purposes'][strtolower($category->getTitle())]['title'] = $category->getTitle();
                $klaroConfig['translations']['en']['purposes'][strtolower($category->getTitle())]['description'] = $category->getDescription();

                // Sorting the sys_categories
                $klaroConfig['purposeOrder'][$category->getUid()] = strtolower($category->getTitle());
            }
        }

        // Sort the sys_categories alphabetically and add a last category 'unknown' for uncategorized services.
        // Only relevant if option 'groupByPurpose' is set to true.
        $klaroConfig['purposeOrder'][] = 'unknown';
        sort($klaroConfig['purposeOrder']);

        // Backwards compatibility
        $klaroConfig = $this->backwardsCompatibility($klaroConfig);

        return $klaroConfig;
    }

    /**
     * Do some backwards compatiblity tasks to avoid as much breaking changes as possible.
     *
     * @todo Maybe we should make this optional via extension configuration or TypoScript to save some performance.
     *
     * @param array $klaroConfig
     * @return array
     */
    private function backwardsCompatibility($klaroConfig)
    {
        // Fallback if cookieName TypoScript-Constant is still in use (this option was renamed to storageName)
        $klaroConfig['storageName'] = $this->settings['cookieName'] ?: $klaroConfig['storageName'];

        /*
         * Reduces JavaScript (Workaround-) Code  which was neccessery for this Bug: https://github.com/KIProtect/klaro/issues/116.
         * We switched that label to sprintf function, but to stay compatible with old locallangs wich where alredy
         * overwritten via TypoScript we leave these here for now. Will be removed it in some later versions.
         */
        if (strpos($klaroConfig['translations']['en']['consentNotice']['description'], '[privacyPage]') !== false) {
            $privacyPageATag = "<a href=" . $klaroConfig['privacyPolicy'] . " title=" . $klaroConfig['translations']['en']['privacyPolicy']['name'] . ">" .
                $klaroConfig['translations']['en']['privacyPolicy']['name'] .
                "</a>";

            $klaroConfig['translations']['en']['consentNotice']['description'] =
                str_replace('[privacyPage]', $privacyPageATag, $klaroConfig['translations']['en']['consentNotice']['description']);
        }

        // Fallback for old Labels used in version < 2.0.0 (klaro changed the object key from 'app' to 'service')
        $klaroConfig['translations']['en']['service']['disableAll']['title'] =
            LocalizationUtility::translate('klaro.app.disableAll.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['disableAll']['title'];

        $klaroConfig['translations']['en']['service']['disableAll']['description'] =
            LocalizationUtility::translate('klaro.app.disableAll.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['disableAll']['description'];

        $klaroConfig['translations']['en']['service']['optOut']['title'] =
            LocalizationUtility::translate('klaro.app.optOut.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['optOut']['title'];

        $klaroConfig['translations']['en']['service']['optOut']['description'] =
            LocalizationUtility::translate('klaro.app.optOut.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['optOut']['description'];

        $klaroConfig['translations']['en']['service']['required']['title'] =
            LocalizationUtility::translate('klaro.app.required.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['required']['title'];

        $klaroConfig['translations']['en']['service']['required']['description'] =
            LocalizationUtility::translate('klaro.app.required.title', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['required']['description'];

        $klaroConfig['translations']['en']['service']['purpose'] =
            LocalizationUtility::translate('klaro.app.purpose', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['purpose'];

        $klaroConfig['translations']['en']['service']['purposes'] =
            LocalizationUtility::translate('klaro.app.purposes', self::EXTKEY) ?:
                $klaroConfig['translations']['en']['service']['purposes'];

        return $klaroConfig;
    }
}