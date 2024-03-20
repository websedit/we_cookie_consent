<?php

namespace Websedit\WeCookieConsent\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Websedit\WeCookieConsent\Domain\Repository\ServiceRepository;

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
 * BackendController
 */
class BackendController extends ActionController
{
    /**
     * Initializes the controller
     *
     * @param \Websedit\WeCookieConsent\Domain\Repository\ServiceRepository $serviceRepository
     */
    public function __construct(protected ServiceRepository $serviceRepository)
    {
    }

    /**
     * Action initializer
     *
     * @return void
     */
    protected function initializeAction(): void
    {
        $pageId = (int)($this->request->getQueryParams()['id'] ?? $this->request->getParsedBody()['id'] ?? 0);

        $querySettings = GeneralUtility::makeInstance(QuerySettingsInterface::class);
        $querySettings->setStoragePageIds([$pageId]);
        $this->serviceRepository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Preview the config
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function gtmWizardAction(): ResponseInterface
    {
        $services = $this->serviceRepository->findBy(['provider' => 'google-tagmanager-service']);

        $blocks = ['tags' => 1, 'triggers' => 1, 'variables' => 1];

        $moduleTemplateFactory = GeneralUtility::makeInstance(ModuleTemplateFactory::class);
        $moduleTemplate = $moduleTemplateFactory->create($this->request);

        $moduleTemplate->assignMultiple([
            'services' => $services,
            'gtmArray' => $this->createGtmArray($services, $blocks)
        ]);

        return $moduleTemplate->renderResponse();
    }

    /**
     * Download the config as JSON File
     *
     * @param array $blocks
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function jsonDownloadAction(array $blocks): ResponseInterface
    {
        $services = $this->serviceRepository->findByProvider('google-tagmanager-service');
        $json = json_encode($this->createGtmArray($services, $blocks), JSON_HEX_TAG);

        return $this->jsonResponse($json)
            ->withHeader('Content-type', 'application/json')
            ->withHeader('Content-Disposition', 'attachment; filename=import-this-to-gtm.json')
        ;
    }

    /**
     * Process the JSON for the Google Tag Manager
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $services
     * @param array $blocks
     * @return array
     */
    private function createGtmArray(QueryResultInterface $services, array $blocks): array
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
