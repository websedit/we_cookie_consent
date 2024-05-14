let CssConsensCookie="display:none;";
let CssToolTip="display:none;"; // Basic tooltip stylization

// Create further TooltipText:
let TextTooltipDe="Datenschutzeinstellungen anpassen";
let TextTooltipNl="Privacy-instellingen aanpassen";
/* Add your further translations here ... */

//get icon default path (esp. for Typo3 12)
let me = null;
let getPath;
let lastSlash = '';
let scripts = document.getElementsByTagName("script")
for (var i = 0; i < scripts.length; ++i) {
  if( scripts[i].src.indexOf('consent-cookie.js')>-1 ) {
    lastSlash = scripts[i].src.lastIndexOf('/');
    getPath = scripts[i].src.substring(0,lastSlash);
    //console.log(getPath);
  }
}

document.addEventListener("DOMContentLoaded", function() {

  let iconContainer = document.createElement('div');
  iconContainer.classList.add('consent-cookie')
  iconContainer.style.cssText = CssConsensCookie;
  // Create image-tags for icon
  let imgTag = document.createElement('img');
  imgTag.src = getPath+'/icon_cookie_settings.svg';
  imgTag.className = "consent-img js-showConsentModal";
  imgTag.style.cssText = "margin-right:0px;width:85px;height:85px;"; // Distance to the text
  imgTag.onmouseover = function() {
    this.src = getPath+'/icon_cookie_settings-hover.svg';
    tooltip.style.display = 'block'; // Tooltip anzeigen beim Überfahren
  };
  imgTag.onmouseout = function() {
    this.src = getPath+'/icon_cookie_settings.svg';
    tooltip.style.display = 'none'; // Show tooltip when hovering over
  };

  // Create Tooltip
  var tooltip = document.createElement('div');
  tooltip.textContent = "Customize privacy settings";
  if ($('html').attr('lang')=="de") tooltip.textContent = TextTooltipDe;
  if ($('html').attr('lang')=="nl") tooltip.textContent = TextTooltipNl;

  tooltip.classList.add('consent-cookie-text');
  tooltip.style.cssText = CssToolTip;

  // Add the image and the tooltip to the container
  iconContainer.appendChild(imgTag);
  iconContainer.appendChild(tooltip);

  document.body.appendChild(iconContainer);



  let  cookieExists = checkCookieExists('klaro');
  if (cookieExists === true) {
    console.log("klaro!!");
    iconContainer.style.display = "flex";
  }
  function checkCookieExists(cookieName) {
    // Split document.cookie by semicolons into an array
    var cookies = document.cookie.split(';');

    // Iterate through the cookies array
    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i];
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
});
