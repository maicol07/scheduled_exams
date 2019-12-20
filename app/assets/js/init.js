/*
 * Translation initialization
 */
const Translator = window.translator.default;
$.getJSON(ROOTDIR + '/locale/' + USER_LANG + '/messages.json', function (data) {
    window.tr = new Translator(data);
});
var tr;
if (window.tr == null) {
    tr = new Translator()
} else {
    tr = window.tr;
}

// MDC
const mdc = window.mdc;

// Auto init
mdc.autoInit();

/*
 * Drawer
 */

// Select DOM elements

const topAppBarElement = $('.mdc-top-app-bar')[0];
const drawerElement = $('.mdc-drawer')[0];
const mainContentEl = $('#main-content')[0];

// Initialize either modal or permanent drawer
const initModalDrawer = () => {
    $('.mdc-drawer-app-content').before('<div class="mdc-drawer-scrim"></div>');

    $(drawerElement).removeClass("mdc-drawer--dismissible").addClass("mdc-drawer--modal");
    const drawer = mdc.drawer.MDCDrawer.attachTo(drawerElement);
    drawer.open = false;

    const topAppBar = mdc.topAppBar.MDCTopAppBar.attachTo(topAppBarElement);
    topAppBar.setScrollTarget(mainContentEl);
    topAppBar.listen('MDCTopAppBar:nav', () => {
        drawer.open = !drawer.open;
    });

    return drawer;
};

const initPermanentDrawer = () => {
    $(drawerElement).removeClass("mdc-drawer--modal");
    const drawer = mdc.drawer.MDCDrawer.attachTo(drawerElement);
    drawer.open = true;

    const topAppBar = mdc.topAppBar.MDCTopAppBar.attachTo(topAppBarElement);
    topAppBar.setScrollTarget(mainContentEl);
    topAppBar.listen('MDCTopAppBar:nav', () => {
        drawer.open = !drawer.open;
    });

    return drawer
};

let drawer = window.matchMedia("(max-width: 900px)").matches ? initModalDrawer() : initPermanentDrawer();

// Toggle between permanent drawer and modal drawer at breakpoint 900px
const resizeHandler = () => {
    if (window.matchMedia("(max-width: 900px)").matches && drawer instanceof mdc.list.MDCList) {
        drawer.destroy();
        drawer = initModalDrawer();
    } else if (window.matchMedia("(min-width: 900px)").matches && drawer instanceof mdc.drawer.MDCDrawer) {
        drawer.destroy();
        drawer = initPermanentDrawer();
    }
};
$(window).resize(resizeHandler);

// MDC Menu initialization
$('.mdc-menu').each((index, element) => {
    const menu = mdc.menu.MDCMenu.attachTo(element);
    $(element).prev('.menu-button').click(() => {
        menu.open = !menu.open;
    });
});

// MDC Ripples initialization
function initRipple(elements) {
    $(elements).each((index, element) => {
        const ripple = mdc.ripple.MDCRipple.attachTo(element);
        if ($(element).hasClass('mdc-icon-button')) {
            ripple.unbounded = true;
        }
    });
}

// MDC Text Input initialization
function initInput(elements = $('.mdc-text-field')) {
    $(elements).each((index, element) => {
        mdc.textField.MDCTextField.attachTo(element);
        if ($(element).hasClass("mdc-text-field--outlined")) {
            mdc.notchedOutline.MDCNotchedOutline.attachTo($(element).find('.mdc-notched-outline')[0])
        }
        if ($(element).hasClass("mdc-text-field--with-leading-icon") || $(element).hasClass("mdc-text-field--with-trailing-icon")) {
            mdc.textField.MDCTextFieldIcon.attachTo($(element).find('.mdc-text-field__icon')[0])
        }
    });
}

// BUTTONS RIPPLES
initRipple($('.mdc-button, .mdc-list-item ,.mdc-icon-button, .mdc-fab'));
// CARDS RIPPLES
initRipple($('.mdc-card__primary-action'));
// INPUTS
initInput();

// Swal2
function initSwalBtn() {
    var buttons = $('.swal2-actions .mdc-button');
    buttons.each((index, btn) => {
        $(btn).html('<div class="mdc-button__ripple"></div><span class="mdc-button__label">' + $(btn).text() + '</span>');
    });
    initRipple(buttons)
}

function renderSwalInput(id, label) {
    return '<br><div class="mdc-text-field mdc-text-field--outlined" style="margin: 1em auto">' +
        '<input type="text" id="' + id + '" class="mdc-text-field__input">' +
        '<div class="mdc-notched-outline">' +
        '<div class="mdc-notched-outline__leading"></div>' +
        '<div class="mdc-notched-outline__notch">' +
        '<label class="mdc-floating-label" for="' + id + '">' + label + '</label>' +
        '</div>' +
        '<div class="mdc-notched-outline__trailing"></div>' +
        '</div>' +
        '</div>';
}

const Swal_md = Swal.mixin({
    customClass: {
        confirmButton: 'mdc-button mdc-button--raised mdc-typography--button',
        cancelButton: 'mdc-button mdc-button--raised mdc-typography--button error-button',
        header: 'mdc-typography',
        content: 'mdc-typography',
        footer: 'mdc-typography'
    },
    buttonsStyling: false,
    showCloseButton: true
});

// noinspection JSUnusedGlobalSymbols
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// GET function
/**
 * Get the value of the parameter specified from a GET request
 *
 * @param parameterName {string} Name of the parameter
 * @returns {string}
 */
function get(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

/* REQUEST CLASS */
class Request {
    url = ROOTDIR + '/app/actions';

    error = function (jqxhr, status, error) {
        Swal_md.fire({
            title: tr.__("Ooops... qualcosa è andato storto!"),
            html: `${tr.__("Si è verificato un errore!")}<br><br>${status} - <b>${error}</b>`,
            icon: "error"
        })
    };

    /**
     * Sends an XHR Post Request
     *
     * @param data
     * @param success
     * @param error
     * @param url
     */
    post(data, success, error = this.error, url = this.url) {
        $.post({
            url: url,
            data: data,
            success: success,
            error: error
        })
    }

    /**
     * Sends an XHR Get Request
     *
     * @param data
     * @param success
     * @param error
     * @param url
     */
    get(data, success, error = this.error, url = this.url) {
        $.get({
            url: url,
            data: data,
            success: success,
            error: error
        })
    }
}

const request = new Request();