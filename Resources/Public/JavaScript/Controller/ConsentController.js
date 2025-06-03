// Consent class
const serviceConfigs = JSON.parse(JSON.stringify(allServiceSettings));

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
		if (settings[settingKey] === 'granted')
		if (settings[settingKey] === 'granted') hasGranted = true;
		else if (settings[settingKey] === 'denied') hasDenied = true;
	});
	
	// If there is at least one "denied" value, return "denied"
	if (hasDenied)  return 'denied';
	
	// If there is only one "granted" value, return "granted"
	if (hasGranted) return 'granted';
	
	// If all values are "not set" or there are no explicit "granted"/"denied" values, return "denied"
	return 'denied';
}

// Function to update the cookie with the final consent values
function updateCookieWithFinalConsent(name, daysToExpire, services) {
	const cookieValue = getCookie(name);
	if (cookieValue !== null) {
		const obj = JSON.parse(decodeURIComponent(cookieValue));
    
		// Evaluate and set the final values for each consent setting
		['ad_storage','analytics_storage','ad_user_data','ad_personalization',
		 'personalization_storage','functionality_storage','security_storage']
		  .forEach(key => {
			obj[key] = evaluateFinalValue(services, key);
		  });
		
		setCookie(name, encodeURIComponent(JSON.stringify(obj)), daysToExpire);
		pushTriggerAfterConsentChanged(obj);
	} else {
		console.log("Cookie name '" + name + "' does not exist.");
	}
}

// Function to push the trigger for gtm after consent changed
function pushTriggerAfterConsentChanged(changes) {
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push({
		event: 'cmp_we_cookie_consent_changed',   // Custom-Event-Name
		we_consent_state: {                       // Add all final states 
			ad_storage             : changes.ad_storage,
			analytics_storage      : changes.analytics_storage,
			ad_user_data           : changes.ad_user_data,
			ad_personalization     : changes.ad_personalization,
			personalization_storage: changes.personalization_storage,
			functionality_storage  : changes.functionality_storage,
			security_storage       : changes.security_storage
		}
	});
}

// Debug function to log all stored service settings
function getAllServiceSettings() {
	// Log all service settings to the console
	console.log(allServiceSettings);
	// Optionally return allServiceSettings;
	// return allServiceSettings;
}

// main controller
let ConsentApp = new function ConsentController() {
	// public functions
	/**
     * Callback function for GoogleTagManager Script to handle consent state changes and fire the dataLayer trigger
     * @param bool state
     * @param object service
     */
    this.consentChanged = function(state, service) {
		// Check if the service is related to Google Tag Manager
		const isGTM = service.name.indexOf('google-tagmanager-service') !== -1;
		if (isGTM) {
			// A) Remap all services - only the service that has just been changed receives either its config values (if state=true) or "denied" all-over.
			allServiceSettings = allServiceSettings.map(tempSettings => {
				if (tempSettings.serviceId !== service.name) {
					// Unmodified copy
					return { 
						serviceId:               tempSettings.serviceId,
						serviceConsent:          tempSettings.serviceConsent,
						ad_storage:              tempSettings.ad_storage,
						analytics_storage:       tempSettings.analytics_storage,
						ad_user_data:            tempSettings.ad_user_data,
						ad_personalization:      tempSettings.ad_personalization,
						functionality_storage:   tempSettings.functionality_storage,
						personalization_storage: tempSettings.personalization_storage,
						security_storage:        tempSettings.security_storage
					};
				}
				
				// Get the original config
				const originalConfig = serviceConfigs.find(original => original.serviceId === tempSettings.serviceId);
				const updated = { serviceId: tempSettings.serviceId, serviceConsent: state };
				
				// For every consent type
				['ad_storage','analytics_storage','ad_user_data','ad_personalization',
			     'personalization_storage','functionality_storage','security_storage']
				  .forEach(key => {
					// if consent is granted: accept from config
					// if consent is denied: always 'denied'
					updated[key] = state ? originalConfig[key] : 'denied';
				});

				return updated;
			});
			
			// B) Evaluate only the granted services
			// Filter allServiceSettings to include only services with serviceConsent = true
			// Update cookie
			const relevantServices = allServiceSettings.filter(relevant => relevant.serviceConsent);
			updateCookieWithFinalConsent(storageName, cookieExpiresAfterDays, relevantServices);
			
			// C) dataLayer-Push based on the state
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({
				event: service.gtm.trigger,
				[service.gtm.variable]: state
			});
		}
				
		// D) Check if the own callback function is allready defined
		if (service.ownCallback) {
			if (typeof window[service.ownCallback] === 'function') {
				window[service.ownCallback](state, service);
            } else {
				console.error('The Callback function ' + service.ownCallback + ' is not yet defined. Please create it first.');
            }
        }
	};

    // constructor (modal and safari)
	(function contruct() {
		document.addEventListener('DOMContentLoaded', function () {
			// Listener for the button on the privacy page, to edit the consent
			document.addEventListener('click', function (event) {
				if (event.target.closest('.js-showConsentModal')) {
					event.preventDefault();
					klaro.show();
				}
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
// End main controller

var optOutLink = document.getElementById("ga-opt-out");

if(optOutLink) {
	optOutLink.onclick = function() {
		document.cookie = 'ga-opt-out=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
		this.innerHTML = "Google Analytics opt-out successful";
		return false;
	}
}

// Functions after window.load():
window.addEventListener('load', function () {
	const iframes = document.querySelectorAll('iframe');

	if (iframes.length > 0) {
		iframes.forEach(function (iframe) {
			let attrDataSrc = iframe.getAttribute('src');
			if (!attrDataSrc) {
				attrDataSrc = iframe.getAttribute('data-src');
			}

			if (attrDataSrc && (attrDataSrc.includes('youtube') || attrDataSrc.includes('vimeo'))) {
				// Adjust measures for videoOverlay similar to iframe:
				const parent = iframe.parentElement;
				const contextNotice = parent.querySelector('.klaro.cm-as-context-notice');

				if (contextNotice) {
					iframe.style.display = 'block';

					// Set the width of .klaro.cm-as-context-notice to the iframe's width
					contextNotice.style.width = iframe.offsetWidth + 'px';

					if (iframe.offsetHeight < contextNotice.offsetHeight) {
						const cmButtons = contextNotice.querySelector('.cm-buttons');
						if (cmButtons) {
							cmButtons.style.marginTop = '1em';
						}
					}
				}
			}
		});
	}

	// Add class for small context-notice box: if video integration within small iframes (width < 300px)
	document.querySelectorAll('.klaro.we_cookie_consent.cm-as-context-notice').forEach(function(element) {
		if (element.offsetWidth <= 300) {
			element.classList.add('notice--minified');
		}
	});

	// Add class to avoid Google to crawl consent info text
	document.querySelectorAll('.klaro.we_cookie_consent .cn-body').forEach(function(element) {
		element.setAttribute('data-nosnippet', 'data-nosnippet');
	});
});