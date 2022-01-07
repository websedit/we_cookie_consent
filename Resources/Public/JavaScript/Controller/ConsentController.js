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
        if (state === true && service.name.indexOf('google-tagmanager-service') !== -1) {
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

        //Check if the own callback function is allready defined
        if (typeof window[service.ownCallback] === 'function') {
            window[service.ownCallback](state, service);
        } else if (service.ownCallback !== '') {
            console.error('The Callback function ' + service.ownCallback + ' is not yet defined. Please create it first.');
        }
    };
};