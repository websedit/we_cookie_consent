<?php

namespace Websedit\WeCookieConsent\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
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
     * Inject a service repository
     *
     * @param ServiceRepository $serviceRepository
     */
    public function injectServiceRepository(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Generate JSON data for the consent Modal
     *
     * @return ResponseInterface
     */
    public function consentAction(): ResponseInterface
    {
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

        return $this->htmlResponse();
    }

    /**
     * @param RequestInterface $request
     */
    protected function renderAssetsForRequest($request): void
    {
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
        if (is_numeric($this->settings['klaro']['privacyPolicy'])) {
            $privacyPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int)$this->settings['klaro']['privacyPolicy'])
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $privacyPage = $this->settings['klaro']['privacyPolicy'];
        }

        if (is_numeric($this->settings['klaro']['poweredBy'])) {
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
            'consentMode' => $this->settings['klaro']['consentMode'] === '1',
            'consentModev2' => $this->settings['klaro']['consentModev2'] === '1',
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

        if(array_key_exists('purposeOrder', $klaroConfig) && is_array($klaroConfig['purposeOrder'])) {
            // Sort the sys_categories alphabetically and add a last category 'unknown' for uncategorized services.
            // Only relevant if option 'groupByPurpose' is set to true.
            ksort($klaroConfig['purposeOrder']);
            $result = array_values($klaroConfig['purposeOrder']);
            $klaroConfig['purposeOrder'] = $result;
            $klaroConfig['purposeOrder'][] = 'unknown';
        }

        return $klaroConfig;
    }
}
