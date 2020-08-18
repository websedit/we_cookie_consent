<?php

namespace Websedit\WeCookieConsent\ViewHelpers\Format;

/**
 * This is a backport of the TYPO3 9 JSON Viewhelper for TYPO3 7 compatibility. Can be removed is support is dropped
 */
class JsonViewHelper extends \Websedit\WeCookieConsent\ViewHelpers\AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed', 'The incoming data to convert, or null if VH children should be used');
        $this->registerArgument('forceObject', 'bool', 'Outputs an JSON object rather than an array', false, false);
    }

    public function render() {
        $value = $this->renderChildren();
        $options = JSON_HEX_TAG;
        if ($this->arguments['forceObject'] !== false) {
            $options = $options | JSON_FORCE_OBJECT;
        }
        return json_encode($value, $options);
    }
}