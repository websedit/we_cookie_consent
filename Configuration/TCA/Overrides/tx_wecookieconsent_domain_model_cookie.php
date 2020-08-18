<?php
defined('TYPO3_MODE') || die();

#TYPO3 8LTS compatibility
if(\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= 9000000) {
    //Change labes back to EXT:lang
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['sys_language_uid']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.language';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['sys_language_uid']['config']['itmes'][0][0] = 'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['l10n_parent']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['t3ver_label']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['hidden']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime';
}

#TYPO3 7LTS compatibility
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= 8000000) {
    //Start- Stop Field
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['size'] = '13';
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['size'] = '13';
    unset($GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['starttime']['config']['renderType']);
    unset($GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['endtime']['config']['renderType']);

    //L10N
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['l10n_parent']['config']['foreign_table_where']  =
        'AND tx_wecookieconsent_domain_model_cookie.pid=###CURRENT_PID### AND tx_wecookieconsent_domain_model_cookie.sys_language_uid IN (-1,0)';

    //Richtext
    $GLOBALS['TCA']['tx_wecookieconsent_domain_model_cookie']['columns']['description']['defaultExtras'] = 'richtext[]';
}