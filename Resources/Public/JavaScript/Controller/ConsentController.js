// Consent class
let ConsentApp = new function ConsentController() {
    //-- global variables ---
    window.dataLayer = window.dataLayer || [];

    //--- public functions ---
    /**
     * Callback function for GoogleTagManager Script to fire the dataLayer trigger
     * @param bool state
     * @param object service
     */
    this.consentChanged = function (state, service) {
        if (state === true) {
            if (service.name.indexOf('google-tagmanager-service') !== -1) {
                let tempObj = {
                    event: service.gtm.trigger
                };
                tempObj[service.gtm.variable] = true;
                window.dataLayer.push(tempObj);

                /*
                //ES6 - https://stackoverflow.com/questions/11508463/javascript-set-object-key-by-variable
                window.dataLayer.push({
                    event: service.name,
                    [service.name]: true
                });
                */
            }
        } else if (state === false) {
            if (service.name.indexOf('google-tagmanager-service') !== -1) {
                let tempObj = {
                    //event: service.gtm.trigger
                    event: service.gtm.trigger
                };
                tempObj[service.gtm.variable] = false;
                window.dataLayer.push(tempObj);
            }
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

    // v2.2.1 - safari -gf20220517
    const isSafari = navigator.vendor && 
           navigator.vendor.indexOf('Apple') > -1 &&
           navigator.userAgent &&
           navigator.userAgent.indexOf('CriOS') == -1 &&
           navigator.userAgent.indexOf('FxiOS') == -1;

    $(function() {
      setTimeout(function() {
        // console.log("isSafari? ("+isSafari+")");
        if (isSafari!=true) {
          $('#klaro').removeClass('safari');
          // $('#klaro').addClass('no-safari');
        } else { 
          $('#klaro').addClass('safari'); 
          // $('#klaro').removeClass('no-safari');
        }
      })
    });
    // v2.2.1 - safari -gf20220517 END.
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
						if (attrDataSrc && ( attrDataSrc.indexOf("youtu") > -1 || attrDataSrc.indexOf("vimeo") > -1 )) {
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
