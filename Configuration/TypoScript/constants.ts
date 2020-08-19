plugin.tx_wecookieconsent_pi1 {
    view {
        # cat=plugin.tx_wecookieconsent_pi1/file; type=string; label=Path to template root (FE)
        templateRootPath >
        # cat=plugin.tx_wecookieconsent_pi1/file; type=string; label=Path to template partials (FE)
        partialRootPath >
        # cat=plugin.tx_wecookieconsent_pi1/file; type=string; label=Path to template layouts (FE)
        layoutRootPath >
    }

    persistence {
        # cat=plugin.tx_wecookieconsent_pi1/01_WEID/100; type=string; label=Storage folder:UID of the cookie sysfolder
        storagePid =
    }

    settings {
        klaro {
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/100; type=string; label=Element ID:DOM ID of klaro
            elementID = klaro
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/101; type=options[Cookie=cookie,Local Storage=localStorage]; label=Storage Method:How Klaro should store the user's preferences. It can be either "cookie" (the default) or "localStorage"
            storageMethod = cookie
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/102; type=string; label=Cookie Name:Cookie name of klaro
            cookieName = klaro
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/102; type=int+; label=Lifetime:Cookie lifetime of klaro in days
            cookieExpiresAfterDays = 365
            # cat=plugin.tx_wecookieconsent_pi1/01_WEID/101; type=string; label=Privacy Page:UID of the privacy page or absolute/relative URL
            privacyPolicy = 1
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/103; type=boolean; label=State:Defines the default state for applications
            default = 0
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/104; type=boolean; label=Must consent:If "mustConsent" is set to true, Klaro will directly display the consent manager modal and not allow the user to close it before having actively consented or declines the use of third-party apps
            mustConsent = 0
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/105; type=boolean; label=Hide Decline all:Hide the decline link
            hideDeclineAll = 0
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/106; type=boolean; label=Hide Learn more:Hide the customization button
            hideLearnMore = 0
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/107; type=string; label=Powered by:UID of the powered by page or absolute/relative URL. Link shown in consent window
            poweredBy = https://consent.websedit.de
            # cat=plugin.tx_wecookieconsent_pi1/03_WEOTHER/108; type=string; label=Language (obsolete):Language selection is based on the page languages. Will be removed in future versions. For language customizations see chapter 6.2 in documentation.
            lang = en
            # cat=plugin.tx_wecookieconsent_pi1/02_WETEMPLATE/100; type=options[Bottom=klaro we_cookie_consent,Top=klaro we_cookie_consent notice--top,Center 1=klaro we_cookie_consent notice--center,Center 2=klaro we_cookie_consent notice--center-floated]; label=Style Prefix:For Custom CSS Styling
            stylePrefix = klaro we_cookie_consent
        }
    }
}

module.tx_wecookieconsent_mod1 {
    view {
        # cat=module.tx_wecookieconsent_mod1/file; type=string; label=Path to template root (BE)
        templateRootPath = EXT:we_cookie_consent/Resources/Private/Backend/Templates/
        # cat=module.tx_wecookieconsent_mod1/file; type=string; label=Path to template partials (BE)
        partialRootPath = EXT:we_cookie_consent/Resources/Private/Backend/Partials/
        # cat=module.tx_wecookieconsent_mod1/file; type=string; label=Path to template layouts (BE)
        layoutRootPath = EXT:we_cookie_consent/Resources/Private/Backend/Layouts/
    }

    persistence {
        # cat=module.tx_wecookieconsent_mod1//a; type=string; label=Default storage PID
        storagePid =
    }
}