<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Websedit.WeCookieConsent',
                'web', // Make module a submodule of 'web'
                'mod1', // Submodule key
                '', // Position
                [
                    'Backend' => 'gtmWizard, jsonDownload',
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:we_cookie_consent/Resources/Public/Icons/user_mod_mod1.svg',
                    'labels' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_mod1.xlf',
                ]
            );

        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_service');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_cookie');
    }
);