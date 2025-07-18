<?php

namespace Websedit\WeCookieConsent\Domain\Model;


/***
 *
 * This file is part of the "we_cookie_consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 websedit AG <extensions@websedit.de>
 *
 ***/

/**
 * Cookie
 */
class Cookie extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Name des Cookies
     * String oder RegEx des Cookie Namens. Diese Cookies werden automatisch gelöscht,
     * wenn der Besucher der Verwendung dieser App nicht zustimmt (z. B. /^_ga_.*$/
     * oder custom_tracker_cookie)
     *
     * @var string
     */
    protected $title = '';

    /**
     * regex
     *
     * @var string
     */
    protected $regex = '';

    /**
     * Beschreibung
     *
     * @var string
     */
    protected $description = '';

    /**
     * maxAge
     *
     * @var string
     */
    protected $maxAge = '';

    /**
     * Returns the title
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description
     *
     * @return string description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the maxAge
     *
     * @return string maxAge
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Sets the maxAge
     *
     * @param string $maxAge
     * @return void
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;
    }

    /**
     * Returns the regex
     *
     * @return string $regex
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Sets the regex
     *
     * @param string $regex
     * @return void
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }
}