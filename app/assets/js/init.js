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
window.inputs = {};
window.selects = {};

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
    const drawer = new mdc.drawer.MDCDrawer(drawerElement);
    drawer.open = false;

    const topAppBar = new mdc.topAppBar.MDCTopAppBar(topAppBarElement);
    topAppBar.setScrollTarget(mainContentEl);
    topAppBar.listen('MDCTopAppBar:nav', () => {
        drawer.open = !drawer.open;
    });

    return drawer;
};

const initPermanentDrawer = () => {
    $(drawerElement).removeClass("mdc-drawer--modal");
    const drawer = new mdc.drawer.MDCDrawer(drawerElement);
    drawer.open = true;

    const topAppBar = new mdc.topAppBar.MDCTopAppBar(topAppBarElement);
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
    const menu = new mdc.menu.MDCMenu(element);
    $(element).prev('.menu-button').click(() => {
        menu.open = !menu.open;
    });
});

// MDC Ripples initialization
function initRipple(elements) {
    $(elements).each((index, element) => {
        const ripple = new mdc.ripple.MDCRipple(element);
        if ($(element).hasClass('mdc-icon-button')) {
            ripple.unbounded = true;
        }
    });
}

// MDC Text Input initialization
function initInput(elements = $('.mdc-text-field')) {
    $(elements).each((index, element) => {
        window.inputs[$(element).attr('id')] = new mdc.textField.MDCTextField(element);
        if ($(element).hasClass("mdc-text-field--outlined")) {
            new mdc.notchedOutline.MDCNotchedOutline($(element).find('.mdc-notched-outline')[0])
        }
        if ($(element).hasClass("mdc-text-field--with-leading-icon") || $(element).hasClass("mdc-text-field--with-trailing-icon")) {
            new mdc.textField.MDCTextFieldIcon($(element).find('.mdc-text-field__icon')[0])
        }
    });
}

// MDC List Initialization
function initList(elements = $('.mdc-list')) {
    $(elements).each((index, element) => {
        const list = new mdc.list.MDCList(element);
        initRipple(list.listElements);
    });
}

// MDC Select Initialization
function initSelect(elements = $('.mdc-select')) {
    $(elements).each((index, element) => {
        window.selects[$(element).attr('id')] = new mdc.select.MDCSelect(element);
        if ($(element).hasClass('mdc-select--with-leading-icon')) {
            const select_icon = new mdc.select.MDCSelectIcon($(element).find('i.mdc-select__icon')[0]);
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
function initSwalBtn(dom) {
    var buttons = $(dom).find('.mdc-button');
    buttons.each((index, btn) => {
        $(btn).html('<div class="mdc-button__ripple"></div>' + $(btn).html());
        var icon = $(btn).find('i.mdc-button__icon');
        if (icon) {
            icon.css('line-height', 'unset')
        }
    });
    initRipple(buttons)
}

function initSwalInput(dom) {
    var inputs = $(dom).find('.mdc-text-field');
    initInput(inputs);
    inputs.each((index, input) => {
        $(input).keyup((event) => {
            if (event.key === 'Enter') {
                $(dom).find('.swal2-actions button.swal2-confirm').click();
            }
        })
    });
    // Focus
    if (inputs.first().length) {
        $(dom).ready(() => {
            window.inputs[inputs.first().attr('id')].focus()
        });
    }
}

function renderOutlinedInput(id, label, value = "", textarea = false) {
    var type = "input";
    if (textarea) {
        type = "textarea"
    }
    return `<br>
        <div class="mdc-text-field ${textarea ? "mdc-text-field--textarea" : "mdc-text-field--outlined"}" style="margin: 1em auto">
        <${type} type="text" id="${id}" name="${id}" value="${value}" class="mdc-text-field__input">${textarea ? value + "</textarea>" : ""}
            <div class="mdc-notched-outline">
                <div class="mdc-notched-outline__leading"></div>
                <div class="mdc-notched-outline__notch">
                    <label class="mdc-floating-label" for="${id}">${label}</label>
                </div>
                <div class="mdc-notched-outline__trailing"></div>
            </div>
        </div>`;
}

/**
 * Renders an outlined select from MDC for Web framework.
 *
 * @param id
 * @param label
 * @param values
 * @param selected
 * @param required
 * @param icon
 * @param icon_as_btn
 * @param width
 * @returns {string}
 */
function renderOutlinedSelect(id, label, values = {}, selected = null, required = false, icon = null, icon_as_btn = false, width = "240px") {
    var list = '';
    Object.keys(values).forEach((value) => {
        list += `
                <li class="mdc-list-item ${(selected === value) ? 'mdc-list-item--selected" aria-selected="true' : ''}" data-value="${value}">
                    ${values[value]}
                </li>`
    });
    return `
    <div id="${id}" class="mdc-select ${required ? 'mdc-select--required' : ''} ${icon ? 'mdc-select--with-leading-icon' : ''}">
        <div class="mdc-select__anchor" style="width: ${width}">
            ${icon ? `<i class="${icon} mdc-select__icon" ${icon_as_btn ? 'tabindex="0" role="button"' : ''} style="font-size: 24px;"></i>` : ''}
            <i class="mdc-select__dropdown-icon"></i>
            <div class="mdc-select__selected-text" ${required ? 'aria-required="true"' : ''}></div>
            <span class="mdc-floating-label">${label}</span>
            <div class="mdc-line-ripple"></div>
        </div>
    
        <div class="mdc-select__menu mdc-menu mdc-menu-surface" style="width: ${width}">
            <ul class="mdc-list">
              ${list}
            </ul>
        </div>
    </div>`
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
    showCloseButton: true,
    onOpen: (dom) => {
        initSwalBtn(dom);
        initSwalInput(dom);
        initSelect($(dom).find('.mdc-select'))
    }
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

/* XHR CLASS */
class XHR {

    constructor(
        url = ROOTDIR + '/app/actions',
        error = function (jqxhr, status, error) {
            Swal_md.fire({
                title: tr.__("Ooops... qualcosa è andato storto!"),
                html: `${tr.__("Si è verificato un errore!")}<br><br><b>${error}</b>`,
                icon: "error"
            })
        }) {
        this.url = url;
        this.error = error;
    }

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

const request = new XHR();