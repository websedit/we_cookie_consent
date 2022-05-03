<?php
defined('TYPO3') || die();

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