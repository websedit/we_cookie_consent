<?php

namespace Websedit\WeCookieConsent\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class EscapeViewHelper extends \Websedit\WeCookieConsent\ViewHelpers\AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    public function render()
    {
        $value = $this->renderChildren();

        if (!is_string($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            return $value;
        }

        // Remove Linebreaks from RTE fields
        $value = preg_replace("/\r|\n/", " ", $value);

        // Escape ', ", \
        $value = addslashes($value);

        return $value;
    }
}