/*! AdBlock Checker v1.0.0 | (c) 2015 Juno_okyo */
function adb_checker(config) {
    // Detect AdBlock. Check is also based on Google Adsense
    if (typeof adblock === 'undefined' && empty(window.google_jobrunner)) {
        if (typeof config === 'object') {
            var url = config.url;
            if (typeof url !== 'undefined' && url.length > 0) {
                // Check redirect optional
                if (config.redirect) {
                    window.top.location.href = url;
                } else if (typeof (warn = config.warning) === 'object') {
                    // Set default value
                    if (typeof warn.text === 'undefined') {
                        warn.text = 'Please disable AdBlock to continue!';
                    }
                    if (typeof warn.button === 'undefined') {
                        warn.button = 'Help me to disable!';
                    }
                    adb_warning(url, warn.text, warn.button);
                }
            }
        }
        return true;
    } else {
        return false;
    }
}

function adb_warning(url, text, button) {
    var html = `
    <div>
        <div class="smoke-base smoke-visible smoke-alert">
            <div class="smokebg"></div>
            <div class="dialog smoke">
                <div class="dialog-inner">
                    <span class="dialog-title">${text}</span>
                    <br>
                    <img src="${getHelpImg()}" alt="AdBlock Checker by Juno_okyo">
                    <div class="dialog-buttons">
                        <a class="mdc-button" href="${url}">
                            <div class="mdc-button__ripple"></div>
                            <i class="mdc-button__icon mdi-outline-help"></i>
                            <span class="mdc-button__label">${button}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
    $("body").append(html);
}

function getHelpImg() {
    var ua = window.navigator.userAgent,
        img = document.location.protocol + '//i.imgur.com/';

    if (ua.indexOf('Chrome/') > -1) {
        img += 'BOqY8vc.png';
    } else if (ua.indexOf('Firefox/') > -1) {
        img += 'dOraTZG.png';
    } else {
        img = -1;
    }

    return img;
}
