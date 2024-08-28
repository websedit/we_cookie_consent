<?php

namespace Websedit\WeCookieConsent\Controller;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function consentAction()
    {
//        $services = $this->serviceRepository->findAll();
//
//        // These two lines are only required for TYPO3 7 backwards compatibility. in TYPO3 >=8 renderAssetsForRequest is used
//        $klaroConfig = $this->klaroConfigBuild($services);
//        $typo3Version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(\TYPO3\CMS\Core\Information\Typo3Version::getVersion());
//
//        $this->view->assignMultiple([
//            'services' => $services,
//            'klaroConfig' => $klaroConfig,
//            'typo3Version' => $typo3Version
//        ]);
        // Backwards compatibility for TYPO3 V10
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() > 10) {
            return $this->htmlResponse();
        }
    }

    /**
     * Show used cookies at the data privacy page
     *
     * @param Websedit\WeCookieConsent\Domain\Model\Service
     * @return \Psr\Http\Message\ResponseInterface
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

        // Backwards compatibility for TYPO3 V10
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() > 10) {
            return $this->htmlResponse();
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     */
    protected function renderAssetsForRequest($request): void
    {
        if (!$this->view instanceof \TYPO3\CMS\Fluid\View\TemplateView) {
            return;
        }

        $services = $this->serviceRepository->findAll();
        $klaroConfig = $this->klaroConfigBuild($services);

        $pageRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);

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
                    $klaroConfig['translations']['en']['purposes'][strtolower($category->getTitle())]['title'] = $category->getTitle();
                    $klaroConfig['translations']['en']['purposes'][strtolower($category->getTitle())]['description'] = $category->getDescription();

                    // Sorting the sys_categories
                    $klaroConfig['purposeOrder'][$this->serviceRepository->getCategorySortingByUid($category->getUid())] = strtolower($category->getTitle());
                }
            }
        }

        if($klaroConfig['purposeOrder']) {
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
