document.addEventListener('DOMContentLoaded', function () {
    if(cookieIconPermanentlyAvailable === '1') {
        createTooltip();
    }
});

function checkCookieExists(cookieName) {
    // Split document.cookie by semicolons into an array
    let cookies = document.cookie.split(';');

    // Iterate through the cookies array
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        // Remove leading spaces (if any)
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        // Check if the cookie name matches
        if (cookie.indexOf(cookieName + '=') == 0) {
            // Cookie with the given name found
            return true;
        }
    }
    // Cookie with the given name not found
    return false;
}

function createTooltip() {
    let tooltip = document.createElement('div');
    let imgTag = document.createElement('img');
    let iconContainer = document.createElement('div');

    imgTag.src = cookieSettingsImgPathDefault;
    imgTag.className = 'consent-img js-showConsentModal';
    imgTag.style.cssText = 'margin-right:0px;width:85px;height:85px;'; //Distance to the text
    imgTag.onmouseover = function () {
        this.src = cookieSettingsImgPathHover;
        tooltip.style.display = 'block'; //Show tooltip when hovering over
    };
    imgTag.onmouseout = function () {
        this.src = cookieSettingsImgPathDefault;
        tooltip.style.display = 'none'; //Hide tooltip when hovering over
    };

    tooltip.textContent = translatedButtonTextCookieSettings;
    tooltip.classList.add('consent-cookie-text');
    tooltip.style.cssText = 'display:none;';

    iconContainer.classList.add('consent-cookie')
    iconContainer.style.cssText = 'display:block;';

    //@todo GF Fragen warum????
    let cookieExists = checkCookieExists('klaro');
    if (cookieExists === true) {
        iconContainer.style.display = 'flex';
    }

    // Add the image and the tooltip to the container
    iconContainer.appendChild(imgTag);
    iconContainer.appendChild(tooltip);

    document.body.appendChild(iconContainer);
}
