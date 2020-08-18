<?php
namespace Websedit\WeCookieConsent\ViewHelpers;

/*
 * Credits to Dirk Persky and his dp_cookieconsent Extension. Thank you for that nice solution to this Problem!
 * @see https://extensions.typo3.org/extension/dp_cookieconsent/
 * 
 * TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper is deprecated since 9.5 and is removed in 10.3
 * But the new Class only exists since 8.6 LTS
 *
 * Support Older TYPO3 Versions
 */
if(class_exists(\TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper::class) === true) {
    /**
     * TYPO3 8.6+
     */
    abstract class AbstractViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {}
} else {
    /**
     * TYPO3 6.2 - 7.6
     */
    abstract class AbstractViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {}
}