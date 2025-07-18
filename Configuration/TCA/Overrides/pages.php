<?php
defined('TYPO3') or die();

//Custom Sysfolder Icon
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-wecookieconsent'] = 'pagetree-folder-contains-cookies';
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'Cookies',
    1 => 'wecookieconsent',
    2 => 'pagetree-folder-contains-cookies'
];