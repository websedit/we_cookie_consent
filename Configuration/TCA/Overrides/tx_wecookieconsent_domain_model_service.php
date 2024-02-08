<?php
defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

$typo3VersionNumber = VersionNumberUtility::convertVersionNumberToInteger(
    VersionNumberUtility::getNumericTypo3Version()
);

if ($typo3VersionNumber < 11000000) {
    // If TYPO3 version is previous version 11. Can be removed when TYO3 V10 support is dropped.

    // Remove the new TCA category field and replace it with old style
    unset($GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['categories']);

    // @extensionScannerIgnoreLine
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
        'we_cookie_consent',
        'tx_wecookieconsent_domain_model_service',
        'categories',
        [
            'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.label',
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.description',
            'fieldConfiguration' => [
                'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.description',
            ],
        ]
    );

    // Change Language field. Required for TYPO3 10 support.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['sys_language_uid']['config'] = [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'special' => 'languages',
        'items' => [
            [
                'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                -1,
                'flags-multiple'
            ]
        ],
        'default' => 0,
    ];

    // Change Start Stop field. Required for TYPO3 10 support.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['eval'] = 'datetime,int';

    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['eval'] = 'datetime,int';
}

if ($typo3VersionNumber < 12000000) {
    // If TYPO3 version is previous version 12. Can be removed when TYO3 V10 and V11 support is dropped.

    // @extensionScannerIgnoreLine
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_service');

    //Breaking: #98024
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['ctr']['cruser_id'] = 'cruser_id';

    // Change start/stop field. Required for TYPO3 V10 and V11 support.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['starttime']['config']['eval'] = 'datetime,int';

    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['endtime']['config']['eval'] = 'datetime,int';

    // Feature: #97035: Required migrated to own array key.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['title']['config']['eval'] .= ',required';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['description']['config']['eval'] .= ',required';

    // Change Checkboxes
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['hidden']['config']['items'] = [
        [
            0 => '',
            1 => '',
            'invertStateDisplay' => true
        ]
    ];
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['state']['config']['items'] = [
        [
            'LLL:EXT:lang/locallang_core.xlf:labels.disabled',
            'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
        ]
    ];
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['preselected']['config']['items'] = [
        [
            'LLL:EXT:lang/locallang_core.xlf:labels.disabled',
            'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
        ]
    ];
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['required']['config']['items'] = [
        [
            'LLL:EXT:lang/locallang_core.xlf:labels.disabled',
            'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
        ]
    ];
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['opt_out']['config']['items'] = [
        [
            'LLL:EXT:lang/locallang_core.xlf:labels.disabled',
            'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
        ]
    ];
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['only_once']['config']['items'] = [
        [
            'LLL:EXT:lang/locallang_core.xlf:labels.disabled',
            'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
        ]
    ];

    // Selectboxen
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_service']['columns']['provider']['config']['items'] = [
        ['Google Analytics', '--div--'],
        ['Google Analytics', 'google-analytics'],
        ['Google Analytics Universal', 'google-analytics-universal'],
        ['Google Tag Manager', '--div--'],
        ['Google Tag Manager', 'google-tagmanager'],
        ['Google Tag Manager - Service', 'google-tagmanager-service'],
        ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.div.other', '--div--'],
        ['Facebook Tracking Pixel', 'facebook'],
        ['Matomo', 'matomo'],
        ['LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.provider.other', 'other'],
        ['Youtube', 'youtube'],
        ['Vimeo', 'vimeo'],
    ];
}