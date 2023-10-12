<?php
defined('TYPO3') || die();

$myTable = "tx_wecookieconsent_domain_model_service";
$GLOBALS['TCA'][$myTable]['columns']['categories'] = [
    'config' => [
       'type' => 'category',
       'label' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.label',
        'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.description',
        'fieldConfiguration' => [
            'description' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_db.xlf:tx_wecookieconsent_domain_model_service.category.description',
        ],
    ]
 ];
 
 \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    $myTable,
    'categories'
 );

