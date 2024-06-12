// Consent class
function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function setCookie(name, value, daysToExpire) {
  var expires = "";
  if (daysToExpire) {
    var date = new Date();
    date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function evaluateFinalValue(serviceSettings, settingKey) {
  // Prüfen, ob mindestens ein "denied" Wert vorhanden ist
  let hasDenied = serviceSettings.some(settings => settings[settingKey] === 'denied');
  if (hasDenied) {
    return 'denied';
  }
  // Prüfen, ob mindestens ein "granted" Wert vorhanden ist
  let hasGranted = serviceSettings.some(settings => settings[settingKey] === 'granted');
  if (hasGranted) {
    return 'granted';
  }

  // Wenn alle Werte "not set" sind oder keine expliziten "granted"/"denied" Werte vorhanden sind, "denied" zurückgeben
  return 'denied';
}

function updateCookieWithFinalConsent(name, daysToExpire, allServiceSettings) {
  var cookieValue = getCookie(name);
  if (cookieValue !== null) {
    var decodedValue = decodeURIComponent(cookieValue);
    var obj = JSON.parse(decodedValue);
    // Auswertung und Setzen der finalen Werte für jede Einstellung
    obj.ad_storage = evaluateFinalValue(allServiceSettings, 'ad_storage');
	obj.analytics_storage = evaluateFinalValue(allServiceSettings, 'analytics_storage');
	obj.ad_user_data = evaluateFinalValue(allServiceSettings, 'ad_user_data');
    obj.ad_personalization = evaluateFinalValue(allServiceSettings, 'ad_personalization');
    obj.personalization_storage = evaluateFinalValue(allServiceSettings, 'personalization_storage');
    obj.functionality_storage = evaluateFinalValue(allServiceSettings, 'functionality_storage');
    var updatedValue = JSON.stringify(obj);
    var encodedValue = encodeURIComponent(updatedValue);
    setCookie(name, encodedValue, daysToExpire);
  } else {
    console.log("Cookie mit dem Namen '" + name + "' existiert nicht.");
  }
}

// Debug-Funktion, um alle gespeicherten Service-Einstellungen zu erhalten
/*
function getAllServiceSettings() {
	// Zum Abrufen und Anzeigen aller Service-Einstellungen:
	//console.log(allServiceSettings);
	return allServiceSettings;
}
*/

let ConsentApp = new function ConsentController() {
	//-- global variables ---
	//window.dataLayer is activated via google-tagmanager.html
	//window.dataLayer = window.dataLayer || [];
	
	//--- public functions ---
	/**
     * Callback function for GoogleTagManager Script to fire the dataLayer trigger
     * @param bool state
     * @param object service
     */
    this.consentChanged = function (state, service) {
		if (service.name.indexOf('google-tagmanager-service') !== -1) {
			if (allServiceSettings.length > 0) {
				let tempSettings = JSON.parse(JSON.stringify(allServiceSettings)); // Erstelle eine tiefe Kopie der Service-Einstellungen.

				// Bearbeite die tempSettings basierend auf dem Zustand und den Service-Einstellungen.
				tempSettings.forEach(tempSetting => {
					// Wenn der aktuelle Service (basierend auf der serviceId) zustimmt, behalte seine Werte.
					// Für alle anderen Services, die serviceConsent = true haben, aber nicht die aktuelle serviceId, setze ihre Werte temporär auf 'denied', wenn state = false.
					if (tempSetting.serviceId !== service.serviceId && tempSetting.serviceConsent === true) {
						if (!state) { // Wenn dem aktuellen Service nicht zugestimmt wurde.
							Object.keys(tempSetting).forEach(key => {
								if (key !== 'serviceId' && key !== 'serviceConsent' && tempSetting[key] !== 'not set') {
									tempSetting[key] = 'denied';
								}
							});
						}
					}
				});
				
				// Filtere die tempSettings, um nur die Services mit serviceConsent = true zu erhalten.
				let relevantSettings = tempSettings.filter(setting => setting.serviceConsent === true);
				// Verwende evaluateFinalValue und updateCookieWithFinalConsent mit den relevanten Einstellungen.
				if (relevantSettings.length > 0) {
					updateCookieWithFinalConsent(storageName, cookieExpiresAfterDays, relevantSettings);
				}
			}
			// Aktualisiere window.dataLayer basierend auf dem Zustand
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

    $(function() {
      setTimeout(function() {
        if (isSafari!=true) {
          $('#klaro').removeClass('safari');
        } else { 
          $('#klaro').addClass('safari'); 
        }
      })
    });
};

//--- Functions after window.load(): ---
$(function() {
		if($('iframe').length>0) {
				var counterOfIframe = 0;
				var attrDataSrc;
				$('iframe').each(function() {
						attrDataSrc=$(this).attr('src'); 
						if (!attrDataSrc) {
								attrDataSrc=$(this).attr('data-src'); 
						}
						if (attrDataSrc && ( attrDataSrc.indexOf("youtube") > -1 || attrDataSrc.indexOf("vimeo") > -1 )) {
								/* Adjust measures for videoOverlay similar to iframe: */
								$(this).parent().find('.klaro.cm-as-context-notice').css({'width':$(this).width()});
								// $(this).parent().find('.klaro.cm-as-context-notice').css({'height':'100%'});  // Activate if height isn't set to 100% by css.
								if ($(this).height() < $(this).parent().find('.klaro.cm-as-context-notice').height()) {
										$(this).parent().find('.klaro.cm-as-context-notice .cm-buttons').css('margin-top','1em');
								}
						}
						counterOfIframe++;
				});
		}

    /**   Add class for small context-notice box  gf20211115 **/
		$('.klaro.we_cookie_consent.cm-as-context-notice').each(function() {
				if ($(this).width() <= 300) {
						$(this).addClass('notice--minified');
				}
		});
    
    /** Add class to avoid Google to crawl consent info text  gf20220623 **/
    $('.klaro.we_cookie_consent .cn-body').each(function() {
      $(this).attr('data-nosnippet','data-nosnippet');
    });
});
