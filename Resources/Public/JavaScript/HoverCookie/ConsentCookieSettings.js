document.addEventListener('DOMContentLoaded', function () {

    if ((cookieIconPermanentlyAvailable === '1') && (checkCookieExists('klaro'))) {
        createTooltip();
    }

    if (cookieIconPermanentlyAvailable === '1') {
        setTimeout(function () {
            const klaroDiv = document.getElementById('klaro');
            const buttons = klaroDiv.querySelectorAll('button');

            function handleClick(event) {
                createTooltip();
                if (document.querySelector('#klaro .cookie-notice') == null && document.querySelector('#klaro .cookie-modal') == null) {
                }
            }

            buttons.forEach(button => {
                button.addEventListener('click', handleClick);
            });


        });

    }

    setTimeout(function () {
        if (document.querySelector('.cm-link')) {
            document.querySelector('.cm-link').addEventListener('click', function () {
                if (document.querySelector('.cm-btn-success')) {
                    document.querySelector('.cm-btn-success').addEventListener('click', function () {
                        createTooltip();
                    });
                }

                if (document.querySelector('.cn-decline')) {
                    document.querySelector('.cn-decline').addEventListener('click', function () {
                        createTooltip();
                    });
                }

                if (document.querySelector('.cm-btn-accept-all')) {
                    document.querySelector('.cm-btn-accept-all').addEventListener('click', function () {
                        createTooltip();
                    });
                }
            });
        }
    }, 1000);
});

function checkCookieExists(cookieName) {
    let cookies = document.cookie.split(';');

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        // Remove leading spaces (if any)
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        // Check if the cookie name matches
        if (cookie.indexOf(cookieName + '=') == 0) {
            return true;
        }
    }
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
    iconContainer.style.display = 'flex';

    // Add the image and the tooltip to the container
    iconContainer.appendChild(imgTag);
    iconContainer.appendChild(tooltip);

    document.body.appendChild(iconContainer);
}
