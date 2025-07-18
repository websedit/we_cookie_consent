<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service',
        'label' => 'provider',
        'label_alt' => 'title',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'provider,name,title,description,purpose,snippet,callback,domain,api_key,gtm_tag_title,gtm_trigger_title,gtm_trigger_name,gtm_variable_title,gtm_variable_name',
        'iconfile' => 'EXT:we_cookie_consent/Resources/Public/Icons/tx_wecookieconsent_domain_model_service.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => ['showitem' => '--palette--;;service_provider, title, description,
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.cookies.label, cookies, 
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.settings.label, state, required, preselected, opt_out, only_once, contextual_consent_only, 
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.identification.label, domain, api_key, 
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.dev.label, snippet, callback, 
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.gtm.label, gtm_tag_title, --palette--;;gtm_trigger, --palette--;;gtm_variable,  
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden, sys_language_uid, l10n_parent, l10n_diffsource, starttime, endtime,
                --div--;LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.tab.google_consent_mode.label, --palette--;;consent_mode, --palette--;;ad_storage, analytics_storage, ad_user_data, ad_personalization, functionality_storage, personalization_storage, security_storage,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, categories,
            '],
    ],
    'palettes' => [
        'service_provider' => [
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.palette.service_provider.label',
            'showitem' => 'provider, name',
        ],
        'gtm_trigger' => [
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.palette.gtm_trigger.label',
            'showitem' => 'gtm_trigger_title, gtm_trigger_name',
        ],
        'gtm_variable' => [
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.palette.gtm_variable.label',
            'showitem' => 'gtm_variable_title, gtm_variable_name',
        ],
        'consent_mode' => [
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.palette.consent_mode_update.label',
            'showitem' => 'ad_storage',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_wecookieconsent_domain_model_service',
                'foreign_table_where' => 'AND {#tx_wecookieconsent_domain_model_service}.{#pid}=###CURRENT_PID### AND {#tx_wecookieconsent_domain_model_service}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'provider' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.label',
            //'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Google Analytics', 'value' => '--div--'],
                    ['label' => 'Google Analytics', 'value' => 'google-analytics'],
                    ['label' => 'Google Analytics Universal', 'value' => 'google-analytics-universal'],
                    ['label' => 'Google Tag Manager', 'value' => '--div--'],
                    ['label' => 'Google Tag Manager', 'value' => 'google-tagmanager'],
                    ['label' => 'Google Tag Manager - Service', 'value' => 'google-tagmanager-service'],
                    ['label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.div.other', 'value' => '--div--'],
                    ['label' => 'Facebook Tracking Pixel', 'value' => 'facebook'],
                    ['label' => 'Matomo', 'value' => 'matomo'],
                    ['label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.other', 'value' => 'other'],
                    ['label' => 'Youtube', 'value' => 'youtube'],
                    ['label' => 'Vimeo', 'value' => 'vimeo'],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'onChange' => 'reload'
        ],
        /*
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.name.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.name.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
            'displayCond' => 'FIELD:provider:=:other',
        ],
        */
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.title.label',
            //'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.description.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.description.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'state' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.state.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.state.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 1,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'preselected' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.preselected.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.preselected.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'required' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.required.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.required.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'opt_out' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.opt_out.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.opt_out.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'only_once' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.only_once.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.only_once.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],

        ],
        /* We prepared this, since klaro offers the option. But for now, we don't see a usecase for it.
        'contextual_consent_only' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.contextual_consent_only.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.contextual_consent_only.description',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        */
        'snippet' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.snippet.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.snippet.description',
            'config' => [
                'type' => 'text',
                'renderType' => 't3editor',
                'format' => 'javascript',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:other',
        ],
        'callback' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.callback.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.callback.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'domain' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.domain.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.domain.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:IN:matomo',
        ],
        'api_key' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.api_key.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.api_key.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:!=:other',
        ],
        'gtm_tag_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_tag_title.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_tag_title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:google-tagmanager-service',
        ],
        'gtm_trigger_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_trigger_title.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_trigger_title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:google-tagmanager-service',
        ],
        'gtm_trigger_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_trigger_name.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_trigger_name.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:google-tagmanager-service',
        ],
        'gtm_variable_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_variable_title.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_variable_title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:google-tagmanager-service',
        ],
        'gtm_variable_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_variable_name.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.gtm_variable_name.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
            'displayCond' => 'FIELD:provider:=:google-tagmanager-service',
        ],
        'cookies' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.cookies.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.cookies.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wecookieconsent_domain_model_cookie',
                'foreign_field' => 'service',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.cookies.button',
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                    'enabledControls' => [
                        'info' => false,
                        'new' => true,
                        'dragdrop' => true,
                        'sort' => false,
                        'hide' => true,
                        'delete' => true,
                        'localize' => true,
                    ]
                ],
            ],
        ],
        'ad_storage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.ad_storage',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.ad_storage',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'analytics_storage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.analytics_storage',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.analytics_storage',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'ad_user_data' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.ad_user_data',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.ad_user_data',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'ad_personalization' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.ad_personalization',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.ad_personalization',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'functionality_storage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.functionality_storage',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.functionality_storage',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'personalization_storage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.personalization_storage',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.personalization_storage',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 0,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'security_storage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.security_storage',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.description.security_storage',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.not_relevant', 0],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.granted', 1],
                    ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.google_consent_mode.denied', 2],
                ],
                'default' => 1,
            ],
            'displayCond' => [
                'OR' => [
                    'FIELD:provider:=:google-analytics',
                    'FIELD:provider:=:google-analytics-universal',
                    'FIELD:provider:=:google-tagmanager-service',
                ]
            ],
        ],
        'categories' => [
            'config' => [
                'type' => 'category'
            ]
        ],
    ],
];
