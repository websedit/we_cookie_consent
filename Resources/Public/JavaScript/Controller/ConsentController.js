// Consent class
// Function to retrieve a cookie by its name
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

// Function to set a cookie with a specified name, value, and expiration date
function setCookie(name, value, daysToExpire) {
	var expires = "";
	if (daysToExpire) {
		var date = new Date();
		date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Function to evaluate the final consent value based on service settings
function evaluateFinalValue(serviceSettings, settingKey) {
	let hasGranted = false;
	let hasDenied = false;
	
	// Iterate through all services and check their consent settings
	serviceSettings.forEach(settings => {
		if (settings[settingKey] === 'granted') {
		hasGranted = true;
		} else if (settings[settingKey] === 'denied') {
		hasDenied = true;
		}
	});
	
	// If there is at least one "denied" value, return "denied"
	if (hasDenied) {
		return 'denied';
	}
	
	// If there is at least one "granted" value, return "granted"
	if (hasGranted) {
		return 'granted';
	}
	
	// If all values are "not set" or there are no explicit "granted"/"denied" values, return "denied"
	return 'denied';
}

// Function to update the cookie with the final consent values
function updateCookieWithFinalConsent(name, daysToExpire, allServiceSettings) {
	var cookieValue = getCookie(name);
	if (cookieValue !== null) {
		var decodedValue = decodeURIComponent(cookieValue);
		var obj = JSON.parse(decodedValue);
    
		// Evaluate and set the final values for each consent setting
		obj.ad_storage = evaluateFinalValue(allServiceSettings, 'ad_storage');
		obj.analytics_storage = evaluateFinalValue(allServiceSettings, 'analytics_storage');
		obj.ad_user_data = evaluateFinalValue(allServiceSettings, 'ad_user_data');
		obj.ad_personalization = evaluateFinalValue(allServiceSettings, 'ad_personalization');
		obj.personalization_storage = evaluateFinalValue(allServiceSettings, 'personalization_storage');
		obj.functionality_storage = evaluateFinalValue(allServiceSettings, 'functionality_storage');
		obj.security_storage = evaluateFinalValue(allServiceSettings, 'security_storage');
	
		var updatedValue = JSON.stringify(obj);
		var encodedValue = encodeURIComponent(updatedValue);
		setCookie(name, encodedValue, daysToExpire);
	} else {
		console.log("Cookie with the name '" + name + "' does not exist.");
	}
}

// Debug function to log all stored service settings
function getAllServiceSettings() {
	// Log all service settings to the console
	console.log(allServiceSettings);
	// Optionally return allServiceSettings;
	// return allServiceSettings;
}

let ConsentApp = new function ConsentController() {
    //--- public functions ---
    /**
     * Callback function for GoogleTagManager Script to handle consent state changes and fire the dataLayer trigger
     * @param bool state
     * @param object service
     */
    this.consentChanged = function (state, service) {
		// Check if the service is related to Google Tag Manager
        if (service.name.indexOf('google-tagmanager-service') !== -1) {
			if (allServiceSettings.length > 0) {
				let tempSettings = JSON.parse(JSON.stringify(allServiceSettings)); // Create a deep copy of the service settings

				// First, apply denied values if state = false
                tempSettings.forEach(tempSetting => {
                    if (tempSetting.serviceId === service.name && !state) {  // If consent is denied
						Object.keys(tempSetting).forEach(key => {
							if (key !== 'serviceId' && key !== 'serviceConsent' && tempSetting[key] === 'granted') {
								tempSetting[key] = 'denied'; // Temporarily set all granted values to denied
							}
						});
					}
				});

                // Then, ensure that granted values for services with consent are not overwritten
				tempSettings.forEach(tempSetting => {
					if (tempSetting.serviceId === service.name && state) {  // If consent is granted
						Object.keys(tempSetting).forEach(key => {
							if (key !== 'serviceId' && key !== 'serviceConsent' && tempSetting[key] === 'denied') {
								tempSetting[key] = 'granted'; // Set all denied values to granted if consent is granted
							}
						});
					}
				});

				// Save the processed tempSettings back to allServiceSettings
				allServiceSettings = tempSettings;
				
				// Filter tempSettings to include only services with serviceConsent = true
				let relevantSettings = tempSettings.filter(setting => setting.serviceConsent === true);
				
				// Use evaluateFinalValue and updateCookieWithFinalConsent with the relevant settings
				if (relevantSettings.length > 0) {
					// Update the cookie with the final consent values
					updateCookieWithFinalConsent(storageName, cookieExpiresAfterDays, relevantSettings);
				}
			}
            // Update window.dataLayer based on the state
			let tempObj = {
				event: service.gtm.trigger,
				[service.gtm.variable]: state
			};
			(function() {
				if (!window.dataLayer) {
					window.dataLayer = [];
				}
			})();
			window.dataLayer.push(tempObj);
		}

        //Check if the own callback function is allready defined
        if (typeof window[service.ownCallback] === "function") {
            window[service.ownCallback](state, service);
        } else if (service.ownCallback !== '') {
            console.error('The Callback function ' + service.ownCallback + ' is not yet defined. Please create it first.');
        }
	};

    //--- constructor ---
    (function contruct() {
        $(document).ready(function () {
            //Listener for the button on the privacy page, to edit the consent
            $(document).on('click', '.js-showConsentModal', function (event) {
                event.preventDefault();
                klaro.show();
            });
        });
    })();

    const isSafari = navigator.vendor &&
        navigator.vendor.indexOf('Apple') > -1 &&
        navigator.userAgent &&
        navigator.userAgent.indexOf('CriOS') == -1 &&
        navigator.userAgent.indexOf('FxiOS') == -1;

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (isSafari!==true) {
                document.getElementById('klaro').classList.remove('safari');
            } else {
                document.getElementById('klaro').classList.add('safari');
            }
        })
    });
	
};

var optOutLink = document.getElementById("ga-opt-out");

if(optOutLink) {
    optOutLink.onclick = function() {
        document.cookie = 'ga-opt-out=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
        this.innerHTML = "Google Analytics opt-out successful";
        return false;
    }
}

//--- Functions after window.load(): ---
$(function () {
    if ($('iframe').length > 0) {
        var counterOfIframe = 0;
        var attrDataSrc;
        $('iframe').each(function () {
            attrDataSrc = $(this).attr('src');
            if (!attrDataSrc) {
                attrDataSrc = $(this).attr('data-src');
            }
            if (attrDataSrc && (attrDataSrc.indexOf("youtube") > -1 || attrDataSrc.indexOf("vimeo") > -1)) {
                /* Adjust measures for videoOverlay similar to iframe: */
                $(this).parent().find('.klaro.cm-as-context-notice').css({'width': $(this).width()});
                // $(this).parent().find('.klaro.cm-as-context-notice').css({'height':'100%'});  // Activate if height isn't set to 100% by css.
                if ($(this).height() < $(this).parent().find('.klaro.cm-as-context-notice').height()) {
                    $(this).parent().find('.klaro.cm-as-context-notice .cm-buttons').css('margin-top', '1em');
                }
            }
            counterOfIframe++;
        });
    }

    /**   Add class for small context-notice box  gf20211115 **/
    $('.klaro.we_cookie_consent.cm-as-context-notice').each(function () {
        if ($(this).width() <= 300) {
            $(this).addClass('notice--minified');
        }
    });

    /** Add class to avoid Google to crawl consent info text  gf20220623 **/
    $('.klaro.we_cookie_consent .cn-body').each(function () {
        $(this).attr('data-nosnippet', 'data-nosnippet');
    });
});