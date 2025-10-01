document.addEventListener('DOMContentLoaded', function () {

    if (checkCookieExists('klaro')) {
        createTooltip();
    }

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
    if (cookieIconPermanentlyAvailable === '1') {
        let iconTooltip = document.createElement('div');
        let iconImgTag = document.createElement('img');
		let iconBtn = document.createElement('button');
        let iconContainer = document.createElement('div');

		// Button
		iconBtn.type = 'button';
		iconBtn.className = 'consent-trigger js-showConsentModal';
		iconBtn.setAttribute('aria-label', translatedButtonTextCookieSettings);
		iconBtn.setAttribute('aria-haspopup', 'dialog');
		
		// Image
		iconImgTag.src = cookieSettingsImgPathDefault;
		iconImgTag.alt = '';
		iconImgTag.setAttribute('aria-hidden', 'true');
		iconImgTag.width = 85; iconImgTag.height = 85;
		iconImgTag.style.cssText = 'margin-right:0px;width:85px;height:85px;';
		
		// Hover/Fokus (Image & Tooltip)
		function showTip(){ iconTooltip.style.display = 'block'; iconImgTag.src = cookieSettingsImgPathHover; }
		function hideTip(){ iconTooltip.style.display = 'none';  iconImgTag.src = cookieSettingsImgPathDefault; }
		
		iconBtn.addEventListener('mouseover', showTip);
		iconBtn.addEventListener('mouseout',  hideTip);
		iconBtn.addEventListener('focusin',   showTip);
		iconBtn.addEventListener('focusout',  hideTip);
		
		// Tooltip
		iconTooltip.textContent = translatedButtonTextCookieSettings;
		iconTooltip.classList.add('consent-cookie-text');
		iconTooltip.style.cssText = 'display:none;';
		// (optional ARIA)
		// tooltip.id = 'consent-tooltip';
		// tooltip.setAttribute('role', 'tooltip');
		// btn.setAttribute('aria-describedby', 'consent-tooltip');
		
		// Container
		iconContainer.classList.add('consent-cookie');
		iconContainer.style.cssText = 'display:flex;';
		
		// DOM
		iconBtn.appendChild(iconImgTag);
		iconContainer.appendChild(iconBtn);
		iconContainer.appendChild(iconTooltip);
		document.body.appendChild(iconContainer);
    }
}
