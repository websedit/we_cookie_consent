<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addStaticFile('we_cookie_consent', 'Configuration/TypoScript', 'Cookie Consent');
