<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            $typo3Version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
            
            if($typo3Version < 10000000) {
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
            } else {
                \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                    'WeCookieConsent',
                    'web', // Make module a submodule of 'web'
                    'mod1', // Submodule key
                    '', // Position
                    [
                        \Websedit\WeCookieConsent\Controller\BackendController::class => 'gtmWizard, jsonDownload',
                    ],
                    [
                        'access' => 'user,group',
                        'icon' => 'EXT:we_cookie_consent/Resources/Public/Icons/user_mod_mod1.svg',
                        'labels' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_mod1.xlf',
                    ]
                );
            }
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_service');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wecookieconsent_domain_model_cookie');
    }
);