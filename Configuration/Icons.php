<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'we-cookie-consent-extension-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:we_cookie_consent/Resources/Public/Icons/ext_icon.svg',
    ],
    'pagetree-folder-contains-cookies' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:we_cookie_consent/Resources/Public/Icons/sysfolder.png',
    ],
];
