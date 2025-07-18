<?php

namespace Websedit\WeCookieConsent\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class VimeoRenderer extends \TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer
{
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Injects the Configuration Manager and loads the settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager An instance of the Configuration Manager
     */
    public function injectConfigurationManager(
        ConfigurationManagerInterface $configurationManager
    ): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns the priority of the renderer
     * This way it is possible to define/overrule a renderer
     * for a specific file type/context.
     * For example create a video renderer for a certain storage/driver type.
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority()
    {
        return 20;
    }

    /**
     * @param FileInterface $file
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false)
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $testing = $extbaseFrameworkConfiguration['plugin.']['tx_wecookieconsent_pi1.']['settings.']['klaro.']['testing'];

        if(!$testing) {
            $options = $this->collectOptions($options, $file);
            $iframe = str_replace(' src="', ' data-name="vimeo" data-src="', parent::render($file, $width, $height, $options, $usedPathsRelativeToCurrentScript));
        } else {
            $iframe = parent::render($file, $width, $height, $options, $usedPathsRelativeToCurrentScript);
        }
        return $iframe;
    }
}
