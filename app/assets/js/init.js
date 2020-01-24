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
window.chipsets = {};
window.data_tables = {};

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

/**
 * Initialize MDC Ripple
 *
 * @param elements {Object}
 */
function initRipple(elements) {
    $(elements).each((index, element) => {
        const ripple = new mdc.ripple.MDCRipple(element);
        if ($(element).hasClass('mdc-icon-button')) {
            ripple.unbounded = true;
        }
    });
}

/**
 * Initialize MDC Input
 *
 * @param elements {Object}
 */
function initInput(elements = $('.mdc-text-field')) {
    $(elements).each((index, element) => {
        window.inputs[$(element).find('input').attr('id')] = new mdc.textField.MDCTextField(element);
        if ($(element).hasClass("mdc-text-field--outlined")) {
            new mdc.notchedOutline.MDCNotchedOutline($(element).find('.mdc-notched-outline')[0])
        }
        if ($(element).hasClass("mdc-text-field--with-leading-icon") || $(element).hasClass("mdc-text-field--with-trailing-icon")) {
            new mdc.textField.MDCTextFieldIcon($(element).find('.mdc-text-field__icon')[0])
        }
        if ($(element).hasClass('mdc-text-field--with-leading-icon')) {
            const select_icon = new mdc.textField.MDCTextFieldIcon($(element).find('i.mdc-text-field__icon')[0]);
        }
    });
}

/**
 * Initialize MDC List
 *
 * @param elements {Object}
 */
function initList(elements = $('.mdc-list')) {
    $(elements).each((index, element) => {
        const list = new mdc.list.MDCList(element);
        initRipple(list.listElements);
    });
}

/**
 * Initialize MDC Select
 *
 * @param elements {Object}
 */
function initSelect(elements = $('.mdc-select')) {
    $(elements).each((index, element) => {
        window.selects[$(element).attr('id')] = new mdc.select.MDCSelect(element);
        if ($(element).hasClass('mdc-select--with-leading-icon')) {
            const select_icon = new mdc.select.MDCSelectIcon($(element).find('i.mdc-select__icon')[0]);
        }
    });
}

/**
 * Initialize MDC Chipset
 *
 * @param elements {Object}
 */
function initChipset(elements = $('.mdc-chip-set')) {
    $(elements).each((index, element) => {
        window.chipsets[$(element).attr('id')] = new mdc.chips.MDCChipSet(element);
    });
}

/**
 * Initialize MDC Data Tables
 *
 * @param elements {Object}
 */
function initDataTables(elements = $('.mdc-data-table')) {
    $(elements).each((index, element) => {
        window.data_tables[$(element).attr('id')] = new mdc.dataTable.MDCDataTable(element);
    });
}

// BUTTONS RIPPLES
initRipple($('.mdc-button, .mdc-list-item ,.mdc-icon-button, .mdc-fab'));
// CARDS RIPPLES
initRipple($('.mdc-card__primary-action'));
// CHIPSETS
initChipset();
// DATA TABLES
initDataTables();
// INPUTS
initInput();

// Swal2

/**
 * Intialize Swal2 MDC Button
 *
 * @param dom {HTMLCollection}
 */
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

/**
 * Initialize Swal2 MDC input
 *
 * @param dom {HTMLCollection}
 */
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
    if (inputs.lenght === 1 && inputs.first().length) {
        $(dom).ready(() => {
            window.inputs[inputs.first().attr('id')].focus()
        });
    }
}

/**
 * Renders an outlined select from MDC for Web framework.
 *
 * @param id {string} ID of the select
 * @param label {string} Label of the select
 * @param properties {Object}
 * @returns {string}
 */
function renderOutlinedInput(id, label, properties = {
    value: "",
    required: false,
    type: 'text',
    min: null, // Works only with NUMBER INPUT type
    icon: null,
    icon_as_btn: false,
    textarea: false,
    style: "margin: 1em auto",
    width: ""
}) {
    var type = "input";
    if (!empty(properties.textarea)) {
        type = "textarea"
    }
    return `
<div class="mdc-text-field ${!empty(properties.textarea) ? "mdc-text-field--textarea" : "mdc-text-field--outlined"}
            ${!empty(properties.icon) ? 'mdc-text-field--with-leading-icon' : ''}"
    style="${!empty(properties.style) ? properties.style : ""}; ${!empty(properties.width) ? `width: ${properties.width}` : ''}">
    ${!empty(properties.icon) ? `<i class="${properties.icon} mdc-text-field__icon"
                                           ${!empty(properties.icon_as_btn) ? 'tabindex="0" role="button"' : ''} style="font-size: 24px;"></i>` : ''}
    <${type} type="${!empty(properties.type) ? properties.type : 'text'}" id="${id}" name="${id}" value="${!empty(properties.value) ? properties.value : ''}"
    class="mdc-text-field__input" ${!empty(properties.required) ? "required" : ""} ${(properties.type === "number" && !empty(properties.min)) ?
        `min="${properties.min}"` : ''}>${!empty(properties.textarea) ? ((!empty(properties.value) ? properties.value : '') + "</textarea>") : ""}
    <div class="mdc-notched-outline">
        <div class="mdc-notched-outline__leading"></div>
        <div class="mdc-notched-outline__notch">
            <label class="mdc-floating-label" for="${id}">${label}</label>
        </div>
        <div class="mdc-notched-outline__trailing"></div>
    </div>
</div>
`;
}

/**
 * Renders an outlined select from MDC for Web framework.
 *
 * @param id {string} ID of the select
 * @param label {string} Label of the select
 * @param properties {Object}
 * @returns {string}
 */
function renderOutlinedSelect(id, label, properties = {
    values: {},
    selected: null,
    required: false,
    icon: null,
    icon_as_btn: false,
    width: "240px"
}) {
    var list = '';
    Object.keys(properties.values).forEach((value) => {
        list += `
                <li class="mdc-list-item ${(properties.selected === value) ? 'mdc-list-item--selected" aria-selected="true' : ''}" data-value="${value}">
                    ${properties.values[value]}
                </li>`
    });
    return `
    <div id="${id}" class="mdc-select mdc-select--outlined ${!empty(properties.required) ? 'mdc-select--required' : ''}
        ${!empty(properties.icon) ? 'mdc-select--with-leading-icon' : ''}" style="display: inline-block">
        <div class="mdc-select__anchor" style="width: ${!empty(properties.width) ? properties.width : '240px'}">
            <div class="mdc-notched-outline">
                <div class="mdc-notched-outline__leading">
                ${!empty(properties.icon) ? `<i class="${properties.icon} mdc-select__icon" ${!empty(properties.icon_as_btn) ? 'tabindex="0" role="button"' : ''}
            style="font-size: 24px;"></i>` : ''}
                </div>
                <div class="mdc-notched-outline__notch">
                    <label class="mdc-floating-label">${label}</label>
                </div>
                <div class="mdc-notched-outline__trailing"></div>
            </div>
            <i class="mdc-select__dropdown-icon"></i>
            <div class="mdc-select__selected-text" ${!empty(properties.required) ? 'aria-required="true"' : ''}></div>
            <div class="mdc-line-ripple"></div>
        </div>
    
        <div class="mdc-select__menu mdc-menu mdc-menu-surface" style="width: ${!empty(properties.width) ? properties.width : '240px'}">
            <ul class="mdc-list">
              ${list}
            </ul>
        </div>
    </div>`
}

/**
 * Renders a MDC chipset
 *
 * @param id {string} ID of the chipset
 * @param properties {Object}
 * @returns {string}
 */
function renderChipset(id, properties = {
    chips: [{}, {}],
    choice: false,
    filter: false,
    input: false
}) {
    var chips_list = '';
    properties.chips.forEach((value) => {
        chips_list += `
        <div class="mdc-chip" role="row">
            <div class="mdc-chip__ripple"></div>
            ${!empty(value.icon) ? `<i class="${value.icon} mdc-chip__icon mdc-chip__icon--leading"></i>` : ''}
            ${!empty(properties.filter) ? `<span class="mdc-chip__checkmark">
                <svg class="mdc-chip__checkmark-svg" viewBox="-2 -3 30 30">
                    <path class="mdc-chip__checkmark-path" fill="none" stroke="black"
                    d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                </svg>
            </span>` : ''}
            <span role="gridcell">
                <span id="${value.id}" role="checkbox" tabindex="0" aria-checked="false" class="mdc-chip__text">${value.text}</span>
            </span>
        </div>
        `
    });
    return `
<div id="${id}" class="mdc-chip-set ${!empty(properties.choice) ? 'mdc-chip-set--choice' : ''} ${!empty(properties.filter) ? 'mdc-chip-set--filter' : ''}
                       ${!empty(properties.input) ? 'mdc-chip-set--input' : ''}" role="grid">
    ${chips_list}
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
    onBeforeOpen: (dom) => {
        initSwalBtn(dom);
        initSwalInput(dom);
        initSelect($(dom).find('.mdc-select'));
        initList($(dom).find('.mdc-list'))
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


class XHR {

    /**
     * XHR constructor
     *
     * @param url {string} If not set, it will point to the actions.php file
     * @param error {function} If not set, it will be used the sample error alert
     */
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
     * @param data {Object}
     * @param success {function}
     * @param error {function} If not set, it will run the function saved in Object property in case of error
     * @param url {string} If not set, it will point to the url saved in Object property
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
     * @param data {Object}
     * @param success {function}
     * @param error {function} If not set, it will run the function saved in Object property in case of error
     * @param url {string} If not set, it will point to the url saved in Object property
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