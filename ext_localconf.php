<?php

use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Websedit\WeCookieConsent\Controller\ConsentController;
use Websedit\WeCookieConsent\Hook\AfterSaveHook;
use Websedit\WeCookieConsent\Resource\Rendering\VimeoRenderer;
use Websedit\WeCookieConsent\Resource\Rendering\YouTubeRenderer;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        $rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
        $rendererRegistry->registerRendererClass(YouTubeRenderer::class);
        $rendererRegistry->registerRendererClass(VimeoRenderer::class);

        ExtensionUtility::configurePlugin(
            'WeCookieConsent',
            'Pi1',
            [
                ConsentController::class => 'consent',
            ],
            [
                ConsentController::class => '',
            ]
        );

        ExtensionUtility::configurePlugin(
            'WeCookieConsent',
            'Pi2',
            [
                ConsentController::class => 'list',
            ],
            [
                ConsentController::class => '',
            ]
        );

        /**
         * Hooks
         */
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = AfterSaveHook::class;

        // Workaround to define custom subcategories in constants editor. Doesn't work in constants.ts
        ExtensionManagementUtility::addTypoScriptConstants('
            # customcategory=plugin.tx_wecookieconsent_pi1=Websedit Cookie Consent
            # customsubcategory=10_WETEST=Testing
            # customsubcategory=20_WEID=IDs
            # customsubcategory=30_WETEMPLATE=Template
            # customsubcategory=40_BEHAVIOUR=Behaviour
            # customsubcategory=50_TOGGLE=Toggles
            # customsubcategory=60_STORAGE=Storage
            # customsubcategory=70_WEOTHER=Other
			# customsubcategory=80_WECONSENTMODE=ConsentMode
        ');
    }
);
