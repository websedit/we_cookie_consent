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
        # cat=plugin.tx_wecookieconsent_pi1/20_WEID/200; type=string; label=Storage folder:UID of the cookie sysfolder
        storagePid =
    }

    settings {
        klaro {
            # cat=plugin.tx_wecookieconsent_pi1/50_TOGGLE/500; type=boolean; label=Accept all:Enable all services, if the consent is not customized.
            acceptAll = 1
            # cat=plugin.tx_wecookieconsent_pi1/30_WETEMPLATE/310; type=string; label=Additonal Class:You can specify an additional class (or classes) that will be added.
            additionalClass =
            # cat=plugin.tx_wecookieconsent_pi1/60_STORAGE/620; type=string; label=Cookie Domain:You can change to cookie domain for the consent manager itself. Use this if you want to get consent once for multiple matching (sub)domains. If empty the current domain will be used.
            cookieDomain =
            # cat=plugin.tx_wecookieconsent_pi1/60_STORAGE/630; type=int+; label=Cookie Lifetime:Expiration of klaro in days. Only relevant if Storage Method is set to Cookie.
            cookieExpiresAfterDays = 365
            # cat=plugin.tx_wecookieconsent_pi1/50_TOGGLE/590; type=boolean; label=Default State:Defines the default state for services. Should be false for GDPR compliance.
            default = 0
            # cat=plugin.tx_wecookieconsent_pi1/30_WETEMPLATE/320; type=string; label=Element ID:DOM ID of klaro.
            elementID = klaro
            # cat=plugin.tx_wecookieconsent_pi1/40_BEHAVIOUR/430; type=boolean; label=Group Services:Activate grouping of services by category.
            groupByPurpose = 0
            # cat=plugin.tx_wecookieconsent_pi1/50_TOGGLE/510; type=boolean; label=Hide Decline all:Hide the decline button.
            hideDeclineAll = 0
            # cat=plugin.tx_wecookieconsent_pi1/50_TOGGLE/520; type=boolean; label=Hide Learn more:Hide the customization button.
            hideLearnMore = 0
            # cat=plugin.tx_wecookieconsent_pi1/40_BEHAVIOUR/410; type=boolean; label=Must consent:If "mustConsent" is set to true, Klaro will directly display the consent manager modal and not allow the user to close it before having actively consented or declines the use of third-party services.
            mustConsent = 0
            # cat=plugin.tx_wecookieconsent_pi1/20_WEID/220; type=string; label=Powered by:UID of the powered by page or absolute/relative URL. Link shown in consent window.
            poweredBy = https://consent.websedit.de
            # cat=plugin.tx_wecookieconsent_pi1/20_WEID/210; type=string; label=Privacy Page:UID of the privacy page or absolute/relative URL.
            privacyPolicy = 1
            # cat=plugin.tx_wecookieconsent_pi1/60_STORAGE/600; type=options[Cookie=cookie,Local Storage=localStorage]; label=Storage Method:How Klaro should store the user's preferences. It can be either "cookie" (the default) or "localStorage".
            storageMethod = cookie
            # cat=plugin.tx_wecookieconsent_pi1/60_STORAGE/610; type=string; label=Storage Name:Name of the klaro Cookie or LocalStorage entry.
            storageName = klaro
            # cat=plugin.tx_wecookieconsent_pi1/30_WETEMPLATE/300; type=options[Bottom=klaro we_cookie_consent,Top=klaro we_cookie_consent notice--top,Center 1=klaro we_cookie_consent notice--center,Center 2=klaro we_cookie_consent notice--center-floated,No Style=]; label=Style Prefix:For Custom CSS Styling.
            stylePrefix = klaro we_cookie_consent
            # cat=plugin.tx_wecookieconsent_pi1/10_WETEST/100; type=boolean; label=Testing mode:Enable klaro only with #klaro-testing e.g. 'https://www.domain.tld/#klaro-testing'. @See:https://kiprotect.com/docs/klaro/integration-testing
            testing = 0
			# cat=plugin.tx_wecookieconsent_pi1/80_WECONSENTMODE/800; type=boolean; label=Consent Mode:Enable Google Consent Mode - Basic implementation
			consentMode = 0
			# cat=plugin.tx_wecookieconsent_pi1/90_WECONSENTMODE/900; type=boolean; label=Consent Mode v2:Enable Google Consent Mode - Advanced implementation
			consentModev2 = 0

            /* Prepared for later use
            # cat=plugin.tx_wecookieconsent_pi1/40_BEHAVIOUR/420; type=boolean; label=Embedded:Will render the Klaro modal and notice without the modal background.
            embedded = 0
            # cat=plugin.tx_wecookieconsent_pi1/50_TOGGLE/530; type=boolean; label=Hide Toggle all:Hide the toggle all button.
            hideToggleAll = 1
            # cat=plugin.tx_wecookieconsent_pi1/10_WETEST/110; type=boolean; label=No Autoload:Prevent Klaro from automatically loading itself when the page is being loaded.
            noAutoLoad = 0
            # cat=plugin.tx_wecookieconsent_pi1/40_BEHAVIOUR/400; type=boolean; label=Notice as modal:Display the consent notice as a modal window, forcing the user to make a consent choice before proceeding to the page.
            noticeAsModal = 0
            */
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