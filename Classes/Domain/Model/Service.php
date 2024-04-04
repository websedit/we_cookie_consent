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
 * Service like Google Analytics, Facebook, Matomo, Youtube, ...
 */
class Service extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories = null;

    /**
     * Der Anbieter des Services
     *
     * @var string
     */
    protected $provider = '';

    /**
     * ID des ScriptTages
     *
     * @var string
     */
    protected $name = '';

    /**
     * Ein einfacher und kurzer Name (z. B. google-analytics)
     *
     * @var string
     */
    protected $title = '';

    /**
     * Beschreibung fürs Frontend
     *
     * @var string
     */
    protected $description = '';

    /**
     * Kategorie
     *
     * @var string
     */
    protected $purpose = '';

    /**
     * Zustand ohne Zustimmung
     *
     * @var bool
     */
    protected $state = false;

    /**
     * Ob diese App standardmässig aktiviert sein soll. Diese Option überschreibt die
     * globale Einstellung.
     *
     * @var bool
     */
    protected $preselected = false;

    /**
     * Die Zustimmung für diese App kann vom Kunden nicht deaktiviert werden
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Die Scripts dieser App werden beim ersten Seitenaufruf geladen. Der Kunde muss
     * die Verwendung explizit verweigern.
     *
     * @var bool
     */
    protected $optOut = false;

    /**
     * Die Scripts dieser App nur einmal laden, auch wenn der Besucher die Zustimmung
     * mehrfach de- und wieder aktiviert.
     *
     * @var bool
     */
    protected $onlyOnce = false;

    /**
     * Einholen der Benutzer Zustimmung auch wenn bereits über Consent bestätigt.
     *
     * @var bool
     */
    protected $contextualConsentOnly = false;

    /**
     * JavaScript Snippet für unbekannte Dienste
     *
     * @var string
     */
    protected $snippet = '';

    /**
     * Diese JavaScript Funktion wird immer dann ausgeführt, wenn diese App vom Kunden
     * zugelassen wird. function (consent, app) { // ... }
     *
     * @var string
     */
    protected $callback = '';

    /**
     * Die Domain unter der, der Service läuft.
     *
     * @var string
     */
    protected $domain = '';

    /**
     * Der API Key, falls zur Identifizierung notwendig.
     *
     * @var string
     */
    protected $apiKey = '';

    /**
     * gtmTagTitle
     *
     * @var string
     */
    protected $gtmTagTitle = '';

    /**
     * Google Tag Manager Trigger Name
     *
     * @var string
     */
    protected $gtmTriggerTitle = '';

    /**
     * gtmTriggerName
     *
     * @var string
     */
    protected $gtmTriggerName = '';

    /**
     * Google Tag Manager Variablenname
     *
     * @var string
     */
    protected $gtmVariableTitle = '';

    /**
     * gtmVariableName
     *
     * @var string
     */
    protected $gtmVariableName = '';

    /**
     * Cookies die von diesem Service eingesetzt werden
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Websedit\WeCookieConsent\Domain\Model\Cookie>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $cookies = null;

    /**
     * @var int
     */
    protected $adStorage;

    /**
     * @var int
     */
    protected $analyticsStorage;

    /**
     * @var int
     */
    protected $adUserData;

    /**
     * @var int
     */
    protected $adPersonalization;

    /**
     * @var int
     */
    protected $functionalityStorage;

    /**
     * @var int
     */
    protected $personalizationStorage;

    /**
     * @var int
     */
    protected $securityStorage;

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->cookies = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the provider
     *
     * @return string provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Sets the provider
     *
     * @param string $provider
     * @return void
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Returns the name
     *
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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
     * Returns the purpose
     *
     * @return string purpose
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Sets the purpose
     *
     * @param string $purpose
     * @return void
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * Returns the state
     *
     * @return bool state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     *
     * @param bool $state
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Returns the boolean state of state
     *
     * @return bool state
     */
    public function isState()
    {
        return $this->state;
    }

    /**
     * Returns the preselected
     *
     * @return bool preselected
     */
    public function getPreselected()
    {
        return $this->preselected;
    }

    /**
     * Sets the preselected
     *
     * @param bool $preselected
     * @return void
     */
    public function setPreselected($preselected)
    {
        $this->preselected = $preselected;
    }

    /**
     * Returns the boolean state of preselected
     *
     * @return bool preselected
     */
    public function isPreselected()
    {
        return $this->preselected;
    }

    /**
     * Returns the required
     *
     * @return bool required
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Sets the required
     *
     * @param bool $required
     * @return void
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Returns the boolean state of required
     *
     * @return bool required
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Returns the optOut
     *
     * @return bool optOut
     */
    public function getOptOut()
    {
        return $this->optOut;
    }

    /**
     * Sets the optOut
     *
     * @param bool $optOut
     * @return void
     */
    public function setOptOut($optOut)
    {
        $this->optOut = $optOut;
    }

    /**
     * Returns the boolean state of optOut
     *
     * @return bool optOut
     */
    public function isOptOut()
    {
        return $this->optOut;
    }

    /**
     * Returns the onlyOnce
     *
     * @return bool onlyOnce
     */
    public function getOnlyOnce()
    {
        return $this->onlyOnce;
    }

    /**
     * Sets the onlyOnce
     *
     * @param bool $onlyOnce
     * @return void
     */
    public function setOnlyOnce($onlyOnce)
    {
        $this->onlyOnce = $onlyOnce;
    }

    /**
     * Returns the boolean state of onlyOnce
     *
     * @return bool onlyOnce
     */
    public function isOnlyOnce()
    {
        return $this->onlyOnce;
    }

    /**
     * Returns the contextualConsentOnly
     *
     * @return bool contextualConsentOnly
     */
    public function getContextualConsentOnly()
    {
        return $this->contextualConsentOnly;
    }

    /**
     * Sets the contextualConsentOnly
     *
     * @param bool $contextualConsentOnly
     * @return void
     */
    public function setContextualConsentOnly($contextualConsentOnly)
    {
        $this->contextualConsentOnly = $contextualConsentOnly;
    }

    /**
     * Returns the boolean state of contextualConsentOnly
     *
     * @return bool contextualConsentOnly
     */
    public function isContextualConsentOnly()
    {
        return $this->contextualConsentOnly;
    }

    /**
     * Returns the callback
     *
     * @return string callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sets the callback
     *
     * @param string $callback
     * @return void
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Returns the domain
     *
     * @return string domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the domain
     *
     * @param string $domain
     * @return void
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Returns the apiKey
     *
     * @return string apiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the apiKey
     *
     * @param string $apiKey
     * @return void
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the snippet
     *
     * @return string snippet
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * Sets the snippet
     *
     * @param string $snippet
     * @return void
     */
    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;
    }

    /**
     * Returns the gtmVariableTitle
     *
     * @return string gtmVariableTitle
     */
    public function getGtmVariableTitle()
    {
        return $this->gtmVariableTitle;
    }

    /**
     * Sets the gtmVariableTitle
     *
     * @param string $gtmVariableTitle
     * @return void
     */
    public function setGtmVariableTitle($gtmVariableTitle)
    {
        $this->gtmVariableTitle = $gtmVariableTitle;
    }

    /**
     * Returns the gtmTriggerTitle
     *
     * @return string gtmTriggerTitle
     */
    public function getGtmTriggerTitle()
    {
        return $this->gtmTriggerTitle;
    }

    /**
     * Sets the gtmTriggerTitle
     *
     * @param string $gtmTriggerTitle
     * @return void
     */
    public function setGtmTriggerTitle($gtmTriggerTitle)
    {
        $this->gtmTriggerTitle = $gtmTriggerTitle;
    }

    /**
     * Adds a Cookie
     *
     * @param \Websedit\WeCookieConsent\Domain\Model\Cookie $cooky
     * @return void
     */
    public function addCooky(\Websedit\WeCookieConsent\Domain\Model\Cookie $cooky)
    {
        $this->cookies->attach($cooky);
    }

    /**
     * Removes a Cookie
     *
     * @param \Websedit\WeCookieConsent\Domain\Model\Cookie $cookyToRemove The Cookie to be removed
     * @return void
     */
    public function removeCooky(\Websedit\WeCookieConsent\Domain\Model\Cookie $cookyToRemove)
    {
        $this->cookies->detach($cookyToRemove);
    }

    /**
     * Returns the cookies
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Websedit\WeCookieConsent\Domain\Model\Cookie> cookies
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Sets the cookies
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Websedit\WeCookieConsent\Domain\Model\Cookie> $cookies
     * @return void
     */
    public function setCookies(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * Returns the gtmTagTitle
     *
     * @return string $gtmTagTitle
     */
    public function getGtmTagTitle()
    {
        return $this->gtmTagTitle;
    }

    /**
     * Sets the gtmTagTitle
     *
     * @param string $gtmTagTitle
     * @return void
     */
    public function setGtmTagTitle($gtmTagTitle)
    {
        $this->gtmTagTitle = $gtmTagTitle;
    }

    /**
     * Returns the gtmTriggerName
     *
     * @return string $gtmTriggerName
     */
    public function getGtmTriggerName()
    {
        return $this->gtmTriggerName;
    }

    /**
     * Sets the gtmTriggerName
     *
     * @param string $gtmTriggerName
     * @return void
     */
    public function setGtmTriggerName($gtmTriggerName)
    {
        $this->gtmTriggerName = $gtmTriggerName;
    }

    /**
     * Returns the gtmVariableName
     *
     * @return string $gtmVariableName
     */
    public function getGtmVariableName()
    {
        return $this->gtmVariableName;
    }

    /**
     * Sets the gtmVariableName
     *
     * @param string $gtmVariableName
     * @return void
     */
    public function setGtmVariableName($gtmVariableName)
    {
        $this->gtmVariableName = $gtmVariableName;
    }

    // Setter and getter methods for adStorage
    public function setAdStorage($adStorage)
    {
        $this->adStorage = $adStorage;
    }

    public function getAdStorage()
    {
        return $this->adStorage;
    }

    // Setter and getter methods for analyticsStorage
    public function setAnalyticsStorage($analyticsStorage)
    {
        $this->analyticsStorage = $analyticsStorage;
    }

    public function getAnalyticsStorage()
    {
        return $this->analyticsStorage;
    }

    // Setter and getter methods for adUserData
    public function setAdUserData($adUserData)
    {
        $this->adUserData = $adUserData;
    }

    public function getAdUserData()
    {
        return $this->adUserData;
    }

    // Setter and getter methods for adPersonalization
    public function setAdPersonalization($adPersonalization)
    {
        $this->adPersonalization = $adPersonalization;
    }

    public function getAdPersonalization()
    {
        return $this->adPersonalization;
    }

    // Setter and getter methods for functionalityStorage
    public function setFunctionalityStorage($functionalityStorage)
    {
        $this->functionalityStorage = $functionalityStorage;
    }

    public function getFunctionalityStorage()
    {
        return $this->functionalityStorage;
    }

    // Setter and getter methods for personalizationStorage
    public function setPersonalizationStorage($personalizationStorage)
    {
        $this->personalizationStorage = $personalizationStorage;
    }

    public function getPersonalizationStorage()
    {
        return $this->personalizationStorage;
    }

    // Setter and getter methods for securityStorage
    public function setSecurityStorage($securityStorage)
    {
        $this->securityStorage = $securityStorage;
    }

    public function getSecurityStorage()
    {
        return $this->securityStorage;
    }
}
