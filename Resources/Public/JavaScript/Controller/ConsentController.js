// Consent class
let ConsentApp = new (function ConsentController() {
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
      if (service.name.indexOf("google-tagmanager-service") !== -1) {
        let tempObj = {
          event: service.gtm.trigger,
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
      if (service.name.indexOf("google-tagmanager-service") !== -1) {
        let tempObj = {
          event: service.gtm.trigger,
        };
        tempObj[service.gtm.variable] = false;
        window.dataLayer.push(tempObj);
      }
    }

    //Check if the own callback function is allready defined
    if (typeof window[service.ownCallback] === "function") {
      window[service.ownCallback](state, service);
    } else if (service.ownCallback !== "") {
      console.error(
        "The Callback function " +
          service.ownCallback +
          " is not yet defined. Please create it first."
      );
    }
  };

  document.addEventListener("DOMContentLoaded", function () {
    // Listener for the button on the privacy page, to edit the consent
    document.addEventListener("click", function (event) {
      var target = event.target;
      if (target.classList.contains("js-showConsentModal")) {
        event.preventDefault();
        klaro.show();
      }
    });
  });

  // v3.0.2 - safari -gf20220517
  const isSafari =
    navigator.vendor &&
    navigator.vendor.indexOf("Apple") > -1 &&
    navigator.userAgent &&
    navigator.userAgent.indexOf("CriOS") == -1 &&
    navigator.userAgent.indexOf("FxiOS") == -1;

  document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
      // console.log("isSafari? ("+isSafari+")");
      var klaroElement = document.getElementById("klaro");
      if (isSafari !== true) {
        klaroElement.classList.remove("safari");
        // klaroElement.classList.add('no-safari');
      } else {
        klaroElement.classList.add("safari");
        // klaroElement.classList.remove('no-safari');
      }
    });
  });
  // v3.0.2 - safari -gf20220517 END.
})();

//--- Functions after window.load(): ---
document.addEventListener("DOMContentLoaded", function () {
  var iframes = document.querySelectorAll("iframe");
  if (iframes.length > 0) {
    var attrDataSrc;
    iframes.forEach(function (iframe) {
      attrDataSrc = iframe.getAttribute("src");
      if (!attrDataSrc) {
        attrDataSrc = iframe.getAttribute("data-src");
      }
      if (
        attrDataSrc &&
        (attrDataSrc.indexOf("youtu") > -1 || attrDataSrc.indexOf("vimeo") > -1)
      ) {
        var parent = iframe.parentNode;
        var klaroContextNotice = parent.querySelector(
          ".klaro.cm-as-context-notice"
        );
        if (klaroContextNotice) {
          klaroContextNotice.style.width = iframe.offsetWidth + "px";
          // Uncomment the line below if height isn't set to 100% by CSS.
          // klaroContextNotice.style.height = '100%';
          if (iframe.offsetHeight < klaroContextNotice.offsetHeight) {
            parent.querySelector(
              ".klaro.cm-as-context-notice .cm-buttons"
            ).style.marginTop = "1em";
          }
        }
      }
    });
  }

  /**   Add class for small context-notice box  gf20211115 **/
  var klaroContextNotices = document.querySelectorAll(
    ".klaro.we_cookie_consent.cm-as-context-notice"
  );
  klaroContextNotices.forEach(function (klaroContextNotice) {
    if (klaroContextNotice.offsetWidth <= 300) {
      klaroContextNotice.classList.add("notice--minified");
    }
  });

  /** Add class to avoid Google to crawl consent info text  gf20220623 **/
  var cnBodies = document.querySelectorAll(".klaro.we_cookie_consent .cn-body");
  cnBodies.forEach(function (cnBody) {
    cnBody.setAttribute("data-nosnippet", "data-nosnippet");
  });
});
