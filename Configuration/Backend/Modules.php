<?php

// Submodulename mod1
use Websedit\WeCookieConsent\Controller\BackendController;

return [
    'web_WeCookieConsentMod1' => [
        'parent' => 'web',
        //'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'iconIdentifier' => 'we-cookie-consent-extension-icon',
        'path' => '/module/web/WeCookieConsentMod1',
        'labels' => 'LLL:EXT:we_cookie_consent/Resources/Private/Language/locallang_mod1.xlf',
        'extensionName' => 'WeCookieConsent',
        'controllerActions' => [
            BackendController::class => [
                'gtmWizard',
                'jsonDownload'
            ],
        ],
    ],
];