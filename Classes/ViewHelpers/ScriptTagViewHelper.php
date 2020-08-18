<?php

namespace Websedit\WeCookieConsent\ViewHelpers;

class ScriptTagViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('snippet', 'string', 'The snippets to make valide', true);
        $this->registerArgument('name', 'string', 'The snippets name to active', true);
    }

	public function render() {
        $snippet = $this->arguments['snippet'];
        $name = $this->arguments['name'];

        $snippet = str_replace('type="text/javascript"', '', $snippet);
        $snippet = str_replace('src="', 'data-src="', $snippet);
        $snippet = str_replace('<script', '<script type="opt-in" data-type="text/javascript" data-name="' . $name . '"', $snippet);

        return $snippet;
    }
}