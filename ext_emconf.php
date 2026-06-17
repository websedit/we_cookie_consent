<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "we_cookie_consent".
 *
 * Auto generated 03-04-2024 18:19
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Cookie Consent for TYPO3 – GDPR Optin',
    'description' => 'Cookie consent panel (opt-in) with GDPR-compliant cookie usage. Supports sitesets. Preconfigured modules for Google Analytics, Facebook, and other commonly used services. Fully customizable to include tracking scripts that set cookies on your website. Support for Google Tag Manager, including Google Consent Mode and Google Consent Mode v2. Easy export to Google Tag Manager. Third-party cookies and scripts are only loaded if active consent has been given. Website visitors can edit their privacy settings at any time. Automatic updating of cookie information when new cookies/scripts are added using a secure consent process. Cookies can be automatically incorporated into the privacy policy via a plugin. Multilingual and full support for desktop, tablet, and mobile devices. Four standard modes for displaying the content solution, including the display of an always-visible icon for accessing privacy settings. Based on Klaro!',
    'category' => 'fe',
    'author' => 'Team websedit',
    'author_email' => 'extensions@websedit.de',
    'author_company' => 'websedit AG',
    'state' => 'stable',
    'uploadfolder' => false,
    'clearcacheonload' => false,
    'clearCacheOnLoad' => 0,
    'version' => '7.0.1',
    'constraints' =>
        array (
            'depends' =>
                array (
                    'typo3' => '14.3.0-14.3.99',
                ),
            'conflicts' =>
                array (
                ),
            'suggests' =>
                array (
                ),
        ),
    'createDirs' => NULL,
);
