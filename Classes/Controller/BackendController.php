<?php

namespace Websedit\WeCookieConsent\Controller;

use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * ServiceController
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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

    // Prepared for TYPO3 12 compatibility
    #public function __construct(protected readonly ModuleTemplateFactory $moduleTemplateFactory){}

    /**
     * Action initializer
     *
     * @return void
     */
    protected function initializeAction()
    {
        // @extensionScannerIgnoreLine
        $pageId = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        // Use this, when support for V10 is dropped
        # $pageUid = (int)($this->request->getQueryParams()['id'] ?? $this->request->getParsedBody()['id'] ?? 0);
        $frameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $persistenceConfiguration = array('persistence' => array('storagePid' => $pageId));
        $this->configurationManager->setConfiguration(array_merge($frameworkConfiguration, $persistenceConfiguration));
    }

    /**
     * Preview the config
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function gtmWizardAction()
    {
        $services = $this->serviceRepository->findByProvider('google-tagmanager-service');

        $blocks = ['tags' => 1, 'triggers' => 1, 'variables' => 1];

        $this->view->assignMultiple([
            'services' => $services,
            'gtmArray' => $this->createGtmArray($services, $blocks)
        ]);

        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() > 10) {
            $moduleTemplateFactory = GeneralUtility::makeInstance(ModuleTemplateFactory::class);
            $moduleTemplate = $moduleTemplateFactory->create($this->request);
            $moduleTemplate->setContent($this->view->render());
            return $this->htmlResponse($moduleTemplate->renderContent());
        }
    }

    /**
     * Download the config as JSON File
     *
     * @param array $blocks
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function jsonDownloadAction($blocks)
    {
        if ($this->response) {
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setHeader('Content-Disposition', 'attachment; filename=import-this-to-gtm.json');
        } else {
            //$this->response is empty in TYPO3 11.1. Maybe a change? Can't find further infos about it at the moment.
            header('Content-type: application/json');
            header('Content-Disposition: attachment; filename=import-this-to-gtm.json');
        }

        $services = $this->serviceRepository->findByProvider('google-tagmanager-service');
        $this->view->assignMultiple([
            'gtmArray' => $this->createGtmArray($services, $blocks)
        ]);

        // Backwards compatibility for TYPO3 V10
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() > 10) {
            return $this->htmlResponse();
        }
    }

    /**
     * Process the JSON for the Google Tag Manager
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $services
     * @param array $blocks
     * @return array
     */
    private function createGtmArray($services, $blocks)
    {
        $gtmArray = [
            "exportFormatVersion" => 2,
            "containerVersion" => [
                "tag" => [],
                "trigger" => [],
                "variable" => [],
                "folder" => [
                    [
                        "accountId" => "0",
                        "containerId" => "0",
                        "folderId" => "0",
                        "name" => "we_cookie_consent"
                    ]
                ]
            ]
        ];

        foreach ($services as $service) {
            //Build Tag
            if ($blocks['tags']) {
                $gtmArray['containerVersion']['tag'][] = [
                    "accountId" => "0",
                    "containerId" => "0",
                    "tagId" => $service->getUid(),
                    "name" => $service->getGtmTagTitle(),
                    "type" => "html",
                    "parameter" => [
                        [
                            "type" => "TEMPLATE",
                            "key" => "html",
                            "value" => "<script>console.log('Service " . $service->getTitle() . " prepared. Please configure via GoogleTagManager');</script>"
                        ]
                    ],
                    "firingTriggerId" => [
                        $service->getUid()
                    ],
                    "parentFolderId" => "0"
                ];
            }

            //Build Trigger
            if ($blocks['triggers']) {
                $gtmArray['containerVersion']['trigger'][] = [
                    "accountId" => "0",
                    "containerId" => "0",
                    "triggerId" => $service->getUid(),
                    "name" => $service->getGtmTriggerTitle(),
                    "type" => "CUSTOM_EVENT",
                    "customEventFilter" => [
                        [
                            "type" => "EQUALS",
                            "parameter" => [
                                [
                                    "type" => "TEMPLATE",
                                    "key" => "arg0",
                                    "value" => "{{_event}}"
                                ],
                                [
                                    "type" => "TEMPLATE",
                                    "key" => "arg1",
                                    "value" => $service->getGtmTriggerName()
                                ]
                            ]
                        ]
                    ],
                    "filter" => [
                        [
                            "type" => "EQUALS",
                            "parameter" => [
                                [
                                    "type" => "TEMPLATE",
                                    "key" => "arg0",
                                    "value" => "{{" . $service->getGtmVariableTitle() . "}}"
                                ],
                                [
                                    "type" => "TEMPLATE",
                                    "key" => "arg1",
                                    "value" => "true"
                                ]
                            ]
                        ]
                    ],
                    "parentFolderId" => "0"
                ];
            }

            //Build Variable
            if ($blocks['variables']) {
                $gtmArray['containerVersion']['variable'][] = [
                    "accountId" => "0",
                    "containerId" => "0",
                    "variableId" => $service->getUid(),
                    "name" => $service->getGtmVariableTitle(),
                    "type" => "v",
                    "parameter" => [
                        [
                            "type" => "INTEGER",
                            "key" => "dataLayerVersion",
                            "value" => "2"
                        ],
                        [
                            "type" => "BOOLEAN",
                            "key" => "setDefaultValue",
                            "value" => "false"
                        ],
                        [
                            "type" => "TEMPLATE",
                            "key" => "name",
                            "value" => $service->getGtmVariableName()
                        ]
                    ],
                    "parentFolderId" => "0"
                ];
            }
        }

        return $gtmArray;
    }
}
