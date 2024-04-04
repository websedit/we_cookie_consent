<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        $rendererRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::class);
        $rendererRegistry->registerRendererClass(\Websedit\WeCookieConsent\Resource\Rendering\YouTubeRenderer::class);
        $rendererRegistry->registerRendererClass(\Websedit\WeCookieConsent\Resource\Rendering\VimeoRenderer::class);

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'WeCookieConsent',
            'Pi1',
            [
                \Websedit\WeCookieConsent\Controller\ConsentController::class => 'consent',
            ],
            [
                \Websedit\WeCookieConsent\Controller\ConsentController::class => '',
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'WeCookieConsent',
            'Pi2',
            [
                \Websedit\WeCookieConsent\Controller\ConsentController::class => 'list',
            ],
            [
                \Websedit\WeCookieConsent\Controller\ConsentController::class => '',
            ]
        );

        /**
         * Hooks
         */
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \Websedit\WeCookieConsent\Hook\AfterSaveHook::class;

        /**
         * Icons
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        //New Content Element Wizard Icon
        $iconRegistry->registerIcon(
            'we_cookie_consent-plugin-pi1',
            $iconRegistry->detectIconProvider('user_plugin_pi1.svg'),
            [
                'source' => 'EXT:we_cookie_consent/Resources/Public/Icons/user_plugin_pi1.svg'
            ]
        );

        //Backendmodul Icon
        $iconRegistry->registerIcon(
            'we_cookie_consent-mod-mod1',
            $iconRegistry->detectIconProvider('user_mod_mod1.svg'),
            [
                'source' => 'EXT:we_cookie_consent/Resources/Public/Icons/user_mod_mod1.svg'
            ]
        );

        //SysFolder Icon
        $iconRegistry->registerIcon(
            'pagetree-folder-contains-cookies',
            $iconRegistry->detectIconProvider('sysfolder.png'),
            [
                'source' => 'EXT:we_cookie_consent/Resources/Public/Icons/sysfolder.png'
            ]
        );

        /**
         * ContentElementWizard for Pi1
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:we_cookie_consent/Configuration/TSConfig/ContentElementWizard.typoscript">'
        );

        // Workaround to define custom subcategories in constants editor. Doesn't work in constants.ts
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
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
