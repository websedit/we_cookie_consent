<?php

namespace Websedit\WeCookieConsent\ViewHelpers;

/**
 * This is a backport of the TYPO3 9 Varaible Viewhelper for TYPO3 7 compatibility. Can be removed is support is dropped
 */
class VariableViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed', 'Value to assign. If not in arguments then taken from tag content');
        $this->registerArgument('name', 'string', 'Name of variable to create', true);
    }

    /**
     * Function can be removed if TYPO3 7 support gets dropped
	 */
	public function render() {
        $value = $this->renderChildren();
        if (!method_exists($this->renderingContext, 'getVariableProvider')) {
            $this->renderingContext->getTemplateVariableContainer()->add($this->arguments['name'], $value);
        } else {
            $this->renderingContext->getVariableProvider()->add($this->arguments['name'], $value);
        }
    }
}