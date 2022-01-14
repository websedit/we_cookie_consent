<?php

namespace Websedit\WeCookieConsent\Controller;

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

    /**
     * Action initializer
     *
     * @return void
     */
    protected function initializeAction()
    {
        $pageId = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        $frameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $persistenceConfiguration = array('persistence' => array('storagePid' => $pageId));
        $this->configurationManager->setConfiguration(\array_merge($frameworkConfiguration, $persistenceConfiguration));
    }

    /**
     * Preview the config
     *
     * @return void
     */
    public function gtmWizardAction()
    {
        $services = $this->serviceRepository->findByProvider('google-tagmanager-service');

        $blocks = ['tags' => 1, 'triggers' => 1, 'variables' => 1];

        $this->view->assignMultiple([
            'services' => $services,
            'gtmArray' => $this->createGtmArray($services, $blocks)
        ]);
    }

    /**
     * Download the config as JSON File
     *
     * @param array $blocks
     *
     * @return void
     */
    public function jsonDownloadAction($blocks)
    {
        if($this->response){
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
