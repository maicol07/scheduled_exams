// Global constants
const LIST_CODE = get('view');

function addListToGrid(data) {
    var inner;
    var grid = $('#lists_grid');
    if (grid.length) {
        inner = grid.find('.mdc-layout-grid__inner').append(`<div class="mdc-layout-grid__cell" id="list_${data.code}"></div>`).find(`#list_${data.code}`)
    } else {
        inner = $('.mdc-layout-grid').append(`<div class="mdc-layout-grid__inner"><div class="mdc-layout-grid__cell" id="list_${data.code}></div></div>`)
            .find('.mdc-layout-grid__cell').first()
    }
    inner.append(`<div class="mdc-card" style="display: none">
                    <div class="mdc-card__primary-action" tabindex="0" onclick="window.location.href = BASEURL + '/app/list?view=${data.code}'">
                        <div class="mdc-card__primary">
                            <h2 class="mdc-typography--headline6">${data.name}</h2>
                        </div>
                    </div>
                    <div class="mdc-card__actions">
                        <div class="mdc-card__action-buttons">
                            <a href="list?view=${data.code}" class="mdc-button mdc-card__action mdc-card__action--button">
                                <div class="mdc-button__ripple"></div>
                                <i class="mdi-outline-open_in_new mdc-button__icon"></i>
                                <span class="mdc-button__label">${tr.__("Apri")}</span>
                            </a>
                        </div>
                        <div class="mdc-card__action-icons">
                            <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                    title="${tr.__("Condividi")}" onclick="shareList('${data.code}')">
                              <i class="mdi-outline-share mdc-button__icon"></i>
                            </button>
                            <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                    title="${tr.__("Elimina")}"
                                    onclick="deleteList('${data.id}', '${data.name}')">
                              <i class="mdi-outline-delete mdc-button__icon"></i>
                            </button>
                        </div>
                    </div>
                </div>`);
    initRipple($(`#list_${data.code}`).find('div.mdc-card .mdc-card__primary-action'));
    inner.find('.mdc-card:hidden').fadeIn(1000);
    var no_lists_div = $('#nolists');
    no_lists_div.fadeOut(1000, () => {
        no_lists_div.remove()
    });
    var left_sidebar_div = $("#left_sidebar_lists");
    if (left_sidebar_div.length) {
        left_sidebar_div.append(`<a class="mdc-menu-classroom-list mdc-list-item" href="list?view=${data.code}">
            <i class="mdi-outline-format_list_numbered mdc-list-item__graphic"></i>
            <span class="mdc-list-item__text mdc-typography--subtitle2">${data.name}</span>
        </a>`).children(':last').hide().fadeIn(1000);
    }
}

async function createList() {
    const {value: list_data} = await Swal_md.fire({
        title: tr.__("Crea lista"),
        html: `${renderOutlinedInput('list_name_input', tr.__("Nome lista"), {
            required: true,
            icon: "mdi-outline-format_list_numbered",
            width: "auto"
        })}<br>
        <br>
        ${renderOutlinedSelect("list_type_input", tr.__("Tipo lista"), {
            values: {
                "FROM_START_DATE": tr.__("Generazione automatica da data di inizio"),
                "AUTO": tr.__("Generazione automatica"),
                "MANUAL": tr.__("Manuale")
            },
            required: true,
            icon: "mdi-outline-layers",
            width: "max-content",
            containerWidth: "auto"
        })}`,
        imageUrl: ROOTDIR + "/app/assets/img/plus.svg",
        imageAlt: tr.__("Crea lista"),
        imageHeight: 150,
        onOpen: (dom) => {
            var select = selects["list_type_input"];
            select.listen('MDCSelect:change', (event) => {
                if (select.value === "FROM_START_DATE") {
                    if (!$(dom).find('#swal2-content #start_date_options').length) {
                        var week = {
                            'monday': tr.__("Lunedì"),
                            'tuesday': tr.__("Martedì"),
                            'wednesday': tr.__("Mercoledì"),
                            'thursday': tr.__("Giovedì"),
                            'friday': tr.__("Venerdì"),
                            'saturday': tr.__("Sabato"),
                            'sunday': tr.__("Domenica")
                        };
                        var chips = [];
                        Object.keys(week).forEach(value => {
                            chips.push({
                                id: value,
                                text: week[value]
                            })
                        });
                        $(dom).find('#swal2-content').append(`
                        <div id="start_date_options" style="display: none">
                            <br>
                            ${renderOutlinedInput("start_date_selection", tr.__("Data di inizio"), {
                            required: true,
                            type: "date",
                            icon: "mdi-outline-today",
                            width: "auto"
                        })}<br>
                            <br>
                            ${tr.__("Giorni in cui si effettua l'interrogazione:")}<br>
                            <br>
                            ${renderChipset("exam_days", {
                            filter: true,
                            chips: chips
                        })}<br>
                            ${renderOutlinedInput('student_num', tr.__("Numero di studenti interrogati per volta"), {
                            type: "number",
                            value: 1,
                            min: 1,
                            icon: 'mdi-outline-people_outline',
                            width: "auto"
                        })}
                        </div>`);
                        $("#start_date_options").fadeIn(1000);
                        var input = $("#start_date_selection");
                        initInput(input.parent());
                        if (!Modernizr.inputtypes.date) {
                            input.focus(() => {
                                dp.show(new Date(), (date) => {
                                    inputs['start_date_selection'].value = date.toLocaleDateString();
                                    $("#start_date_selection").attr('data-timestamp', date.toISOString());
                                })
                            });
                        }

                        var chips = $("#exam_days");
                        initChipset(chips);

                        var num = $("#student_num");
                        initInput(num.parent())
                    }
                } else {
                    var div = $(dom).find('#start_date_options');
                    if (div) {
                        div.fadeOut(750, () => {
                            div.remove()
                        });
                    }
                }
            })
        },
        preConfirm: () => {
            var data = {
                name: $('#list_name_input').val(),
                type: selects["list_type_input"].value
            };
            if (data.type === "FROM_START_DATE") {
                var date_input = $("#start_date_selection");
                data.start_date = !empty(date_input.attr('data-timestamp')) ? date_input.attr('data-timestamp') : date_input.val();
                data.weekdays = [];
                chipsets.exam_days.selectedChipIds.forEach((id) => {
                    data.weekdays.push($(`#${id}`).find('span.mdc-chip__text').attr('id'))
                });
                data.quantity = $("#student_num").val();
            }
            return data
        }
    });
    if (!empty(list_data.type) && !empty(list_data.name)) {
        Swal.showLoading();
        list_data.action = 'create_list';
        list_data.classroom_code = CLASSROOM_CODE;
        request.post(list_data, (data) => {
            addListToGrid(data);
            Swal.hideLoading();
            Toast.fire({
                title: tr.__("Lista creata!"),
                icon: "success"
            });
        })
    } else {
        dp.hide();
        Toast.fire({
            title: tr.__("Alcuni dati non sono stati immessi!"),
            icon: 'error'
        })
    }
}

function deleteList(id, name) {
    Swal_md.fire({
        title: tr.__("Sei sicuro di voler eliminare la lista %s?", name),
        html: tr.__(`Non sarà poi possibile ripristinarla! Perderai, inoltre, tutte le informazioni associate ad essa!<br>Sei sicuro di voler continuare?`),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: tr.__("Sì, eliminala!"),
        cancelButtonText: tr.__("No, non eliminarla!"),
    }).then((result) => {
        if (result.value) {
            request.post({
                action: "delete_list",
                id: id
            }, (data) => {
                var div = $(`#list_${data.code}`);
                div.fadeOut(1000, () => {
                    div.remove()
                });
                Toast.fire({
                    title: tr.__("Lista eliminata!"),
                    icon: "success"
                });
                if (!window.hasOwnProperty('CLASSROOM_CODE')) {
                    window.location.replace(BASEURL + '/app/classroom?view=' + data.classroom_code)
                }
            })
        }
    });
}

function shareList(code) {
    Swal_md.fire({
        title: tr.__("Condividi la lista"),
        html: `${tr.__(`Il codice della lista è :code:.<br>Il seguente indirizzo pubblico, invece, indica l'indirizzo a cui è 
possibile visiualizzare la lista (<b>Attenzione! Chiunque possieda il link può visualizzare le informazioni contenuti in essa!</b>): :link:`, {
            ':code:': `<pre>${code}</pre>`, ':link:': `<br><br><code>
<a href="${BASEURL}/app/list?view=${code}">${BASEURL}/app/list?view=${code}</a></code>`
        })}`
    });
}

// List page
function editList() {
    var card = $('#list_info');
    var img = card.find('.mdc-card__media');

    // Image upload
    img.append(`
                <div class="hvr-img">
                    <input type="file" name="list_img" class="upload" id="image_input" accept="image/*">
                </div>
                <i class="mdi-outline-camera_alt"></i>`);
    card.find('.mdc-card__primary-action').click((event) => {
        if (!$(event.target).is('#image_input')) {
            $('#image_input').trigger('click');
        }
    });
    $('#image_input').on('change', () => {
        var file = $("#image_input")[0].files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
            img.css('background-image', `url(${reader.result})`);
        };
        if (file && file.type.match("image.*")) {
            reader.readAsDataURL(file);
        } else {
            Swal_md.fire({
                title: tr.__("File inserito non valido!"),
                html: tr.__(`Non hai inserito nessun file oppure il file inserito non è un file immagine (deve avere una fra le seguenti estensioni:<br>
<code>.jpe, .jpg, .jpeg, .gif, .png, .bmp, .ico, .svg, .svgz, .tif, .tiff, .ai, .drw, .pct, .psp, .xcf, .psd, .raw</code>`),
                icon: "error"
            })
        }
    });

    // Class name
    var name = card.find('.mdc-card__primary div.mdc-typography--headline6');
    name.replaceWith(renderOutlinedInput("list_name", tr.__("Nome lista"), {
        value: name.text().trim(),
        icon: "mdi-outline-format_list_numbered",
    }));

    // Class description
    var description = card.find('.mdc-card__secondary div.mdc-typography--subtitle2');
    description.replaceWith(renderOutlinedInput("list_description", tr.__("Descrizione classe"), {
        value: description.text().trim(),
        textarea: true,
    }));
    initInput($('input#list_name, textarea#list_description').parent('.mdc-text-field'));

    // Edit button
    var button = card.find('.mdc-card__actions button#edit_button');
    button.attr("title", tr.__("Salva")).attr("onclick", "saveList()").attr('id', 'save_button');
    button.find('i.mdc-button__icon').removeClass().addClass('mdc-button__icon mdi-outline-save')
}

function saveList() {
    Swal.showLoading();
    request.post({
        action: 'update_list',
        code: LIST_CODE,
        name: $('input#list_name').val(),
        description: $('textarea#list_description').val(),
        image: $('#list_info .mdc-card__media').css('background-image').slice(4, -1).replace(/"/g, "")
    }, () => {
        var card = $('#list_info');
        var img = card.find('.mdc-card__media');

        // Image
        img.find('div.hvr-img').remove();
        img.find('i').remove();
        card.find('.mdc-card__primary-action').off('click');

        // Class name
        var name = $('input#list_name');
        name.parent('div.mdc-text-field').replaceWith(`<div class="mdc-typography--headline6">${name.val()}</div>`);

        // Class description
        var description = $('textarea#list_description');
        description.parent('div.mdc-text-field').replaceWith(`<div class="mdc-typography--subtitle2">${description.val()}</div>`);

        // Edit button
        var button = card.find('.mdc-card__actions button#save_button');
        button.attr("title", tr.__("Modifica")).attr("onclick", "editList()").attr('id', 'edit_button');
        button.find('i.mdc-button__icon').removeClass().addClass('mdc-button__icon mdi-outline-edit');

        Toast.fire({
            title: tr.__("Lista modificata!"),
            icon: 'success'
        })
    })
}

// TABLE ROWS
function addRow(table = $('table')) {
    Swal_md.fire();
    table.find('tbody').append(`
        <tr id="list_row_${row_data.id}" class="mdc-data-table__row">
            <td class="mdc-data-table__cell">
                <div class="mdc-chip-set" role="grid">
                    <div class="mdc-chip" role="row">
                        <div class="mdc-chip__ripple"></div>
                        <img src="${row_data.student.image}" class="mdc-chip__icon mdc-chip__icon--leading" alt="${row_data.student.name}">
                        <span role="gridcell">
                            <span role="button" tabindex="0" class="mdc-chip__text">${row_data.student.name}</span>
                        </span>
                    </div>
                </div>
            </td>
            <td class="mdc-data-table__cell">
                <span id="unix_timestamp" style="display: none">' . strtotime($row->date) . '</span>
                ' . Utils::getLocaleDate($row->date, $lang) . '
            </td>
            <td class="mdc-data-table__cell">
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                            title="' . __("Modifica") . '" onclick="editRow(\\'list_row_' . $list->code . '\\')">
                      <i class="mdi-outline-edit mdc-button__icon"></i>
                </button>
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                            title="' . __("Elimina") . '" onclick="deleteRow(\\'list_row_' . $list->code . '\\')">
                      <i class="mdi-outline-delete mdc-button__icon"></i>
            </td>
        </tr>`);
    var no_rows_div = $("#no_rows");
    if (no_rows_div.length) {
        no_rows_div.append(`<a class="mdc-button" href="prints/list?view=<?php echo $list->code ?>" target="_blank" style="float: right;">
                        <div class="mdc-button__ripple"></div>
                        <i class="mdi-outline-print mdc-button__icon"></i>
                        <span class="mdc-button__label">${tr.__("Stampa")}</span>
                    </a>`)
    }
}

function deleteRow() {
    Swal_md.fire({})
}