<?php
defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

call_user_func(
    function () {
        $typo3VersionNumber = VersionNumberUtility::convertVersionNumberToInteger(
            VersionNumberUtility::getNumericTypo3Version()
        );

        if ($typo3VersionNumber < 12000000) {
            // If TYPO3 version is previous version 12. Can be removed when TYPO3 V10 and V11 support is dropped.

            // @extensionScannerIgnoreLine
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
);