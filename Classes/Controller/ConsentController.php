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

        //These two lines are only required for TYPO3 7 backwards compatibility. in TYPO3 >=8 renderAssetsForRequest is used
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
            //No custom findByUids function to keep the sorting
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
        if (is_numeric($this->settings['klaro']['privacyPolicy'])) {
            $privacyPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int) $this->settings['klaro']['privacyPolicy'])
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $privacyPage = $this->settings['klaro']['privacyPolicy'];
        }
        
        if (is_numeric($this->settings['klaro']['poweredBy'])) {
            $poweredByPage = $this->uriBuilder
                ->reset()
                ->setTargetPageUid((int) $this->settings['klaro']['poweredBy'])
                ->setCreateAbsoluteUri(true)
                ->build();
        } else {
            $poweredByPage = $this->settings['klaro']['poweredBy'];
        }

        $klaroConfig = [
            'elementID' => $this->settings['klaro']['elementID'],
            'storageMethod' => $this->settings['klaro']['storageMethod'],
            'cookieName' => $this->settings['klaro']['cookieName'],
            'cookieExpiresAfterDays' => $this->settings['klaro']['cookieExpiresAfterDays'],
            'privacyPolicy' => $privacyPage,
            'default' => $this->settings['klaro']['default'] === '1',
            'mustConsent' => $this->settings['klaro']['mustConsent'] === '1',
            'hideDeclineAll' => $this->settings['klaro']['hideDeclineAll'] === '1',
            'hideLearnMore' => $this->settings['klaro']['hideLearnMore'] === '1',
            'lang' => $this->settings['klaro']['lang'],
            'poweredBy' => $poweredByPage,
            'translations' => [
                'en' => [
                    'consentModal' => [
                        'title' => LocalizationUtility::translate('klaro.consentModal.title', 'we_cookie_consent'),
                        'description' => LocalizationUtility::translate('klaro.consentModal.description', 'we_cookie_consent'),
                        'privacyPolicy' => [
                            'text' => LocalizationUtility::translate('klaro.consentModal.privacyPolicy.text', 'we_cookie_consent'),
                            'name' => LocalizationUtility::translate('klaro.consentModal.privacyPolicy.name', 'we_cookie_consent')
                        ]
                    ],
                    'consentNotice' => [
                        'description' => LocalizationUtility::translate('klaro.consentNotice.description', 'we_cookie_consent'),
                        'changeDescription' => LocalizationUtility::translate('klaro.consentNotice.changeDescription', 'we_cookie_consent'),
                        'learnMore' => LocalizationUtility::translate('klaro.consentNotice.learnMore', 'we_cookie_consent')
                    ],
                    'app' => [
                        'disableAll' => [
                            'title' => LocalizationUtility::translate('klaro.app.disableAll.title', 'we_cookie_consent'),
                            'description' => LocalizationUtility::translate('klaro.app.disableAll.description', 'we_cookie_consent')
                        ],
                        'optOut' => [
                            'title' => LocalizationUtility::translate('klaro.app.optOut.title', 'we_cookie_consent'),
                            'description' => LocalizationUtility::translate('klaro.app.optOut.description', 'we_cookie_consent')
                        ],
                        'required' => [
                            'title' => LocalizationUtility::translate('klaro.app.required.title', 'we_cookie_consent'),
                            'description' => LocalizationUtility::translate('klaro.app.required.description', 'we_cookie_consent')
                        ],
                        'purpose' => LocalizationUtility::translate('klaro.app.purpose', 'we_cookie_consent'),
                        'purposes' => LocalizationUtility::translate('klaro.app.purposes', 'we_cookie_consent')
                    ],
                    'purposes' => [
                        'unknown' => LocalizationUtility::translate('klaro.purposes.unknown', 'we_cookie_consent')
                    ],
                    'ok' => LocalizationUtility::translate('klaro.ok', 'we_cookie_consent'),
                    'save' => LocalizationUtility::translate('klaro.save', 'we_cookie_consent'),
                    'acceptSelected' => LocalizationUtility::translate('klaro.save', 'we_cookie_consent'),
                    'decline' => LocalizationUtility::translate('klaro.decline', 'we_cookie_consent'),
                    'close' => LocalizationUtility::translate('klaro.close', 'we_cookie_consent'),
                    'poweredBy' => LocalizationUtility::translate('klaro.poweredBy', 'we_cookie_consent')
                ]
            ],
            'apps' => []
        ];

        foreach ($services as $service) {
            foreach ($service->getCategories() as $category) {
                $klaroConfig['translations']['en']['purposes'][strtolower($category->getTitle())] = $category->getTitle();
            }
        }

        return $klaroConfig;
    }
}
