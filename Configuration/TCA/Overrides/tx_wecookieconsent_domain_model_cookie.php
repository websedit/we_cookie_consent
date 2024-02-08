<?php
defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

$typo3VersionNumber = VersionNumberUtility::convertVersionNumberToInteger(
    VersionNumberUtility::getNumericTypo3Version()
);

if ($typo3VersionNumber < 11000000) {
    // If TYPO3 version is previous version 11. Can be removed when TYO3 V10 support is dropped.

    // Change Language field. Required for TYO3 10 support.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['sys_language_uid']['config'] = [
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
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['eval'] = 'datetime,int';

    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['eval'] = 'datetime,int';
}

if ($typo3VersionNumber < 12000000) {
    // If TYPO3 version is previous version 12. Can be removed when TYO3 V10 and V11 support is dropped.

    // @extensionScannerIgnoreLine
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_cookie');

    //Breaking: #98024
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['ctr']['cruser_id'] = 'cruser_id';

    // Change start/stop field. Required for TYPO3 V10 and V11 support.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['eval'] = 'datetime,int';

    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['type'] = 'input';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['eval'] = 'datetime,int';

    // Feature: #97035: Required migrated to own array key.
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['title']['config']['eval'] .= ',required';

    // Change Checkboxes
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['hidden']['config']['items'] = [
        [
            0 => '',
            1 => '',
            'invertStateDisplay' => true
        ]
    ];
}