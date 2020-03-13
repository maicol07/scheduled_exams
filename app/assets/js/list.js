// Global constants
const LIST_CODE = get('view');

function addListToGrid(data) {
    var inner;
    var grid = $('#lists_grid .mdc-layout-grid__inner');
    var no_lists_div = $('#nolists');
    if (grid.length) {
        inner = grid.append(`<div class="mdc-layout-grid__cell" id="list_${data.code}"></div>`).find(`#list_${data.code}`)
    } else {
        inner = no_lists_div.before(`
            <div class="mdc-layout-grid__inner">
                <div class="mdc-layout-grid__cell" id="list_${data.code}"></div>
            </div>`).prev('.mdc-layout-grid__inner').find('.mdc-layout-grid__cell').first()
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
                            <a class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                            title="${tr.__("Stampa")}" href="prints/templates/list?view=${data.code}" target="_blank">
                              <i class="mdi-outline-print mdc-button__icon"></i>
                            </a>
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
    no_lists_div.fadeOut(1000, () => {
        no_lists_div.remove()
    });
    var left_sidebar_div = $("#left_sidebar_lists");
    if (left_sidebar_div.length) {
        left_sidebar_div.append(`<a id="ls_list_${data.code}" class="mdc-menu-classroom-list mdc-list-item" href="list?view=${data.code}">
            <i class="mdi-outline-format_list_numbered mdc-list-item__graphic"></i>
            <span class="mdc-list-item__text mdc-typography--subtitle2">${data.name}</span>
        </a>`).children(':last').hide().fadeIn(1000);
        initRipple($(`ls_list_${data.code}`));
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
    if (!empty(typeof list_data) && !empty(list_data.type) && !empty(list_data.name)) {
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
                    div.remove();
                    if (!$('#lists_grid .mdc-layout-grid__cell').length) {
                        var grid = $('#lists_grid');
                        grid.before(`<div id="lists_grid" class="mdc-layout-grid__cell" style="flex: 1">
                            <div id="nolists" style="text-align: center; display:none" xmlns="http://www.w3.org/1999/html">
                                <img src="${ROOTDIR}/app/assets/img/undraw/no_data.svg" alt="${tr.__("Nessuna lista")}"
                                style="width: 500px; margin-bottom: 20px"><br>
                                <span class="mdc-typography--headline5">${tr.__("Nessuna lista")}</span><br>
                                <span>${tr.__("Puoi aggiungere nuove liste dal pulsante in basso a destra")}</span>
                            </div>
                        </div>`);
                        var no_lists = $("#nolists");
                        no_lists.fadeIn(1000);
                        no_lists.prev('h3').fadeOut(1000, () => {
                            no_lists.prev('h3').remove()
                        });
                        grid.fadeOut(1000, () => {
                            grid.remove()
                        })
                    }
                });

                var ls_list = $(`#ls_list_${data.code}`);
                ls_list.fadeOut(1000, () => {
                    ls_list.remove()
                });

                Toast.fire({
                    title: tr.__("Lista eliminata!"),
                    icon: "success"
                });
                if (!empty(LIST_CODE)) {
                    window.location.replace(BASEURL + '/app/classroom?view=' + data.classroom_code)
                }
            })
        }
    });
}

function shareList(code) {
    Swal_md.fire({
        title: tr.__("Condividi la lista"),
        html: `${tr.__(`Il codice della lista è %code%.<br>Il seguente indirizzo pubblico, invece, indica l'indirizzo a cui è 
possibile visiualizzare la lista (<b>Attenzione! Chiunque possieda il link può visualizzare le informazioni contenuti in essa!</b>): %link%`, {
            '%code%': `<pre>${code}</pre>`, '%link%': `<br><br><code>
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
async function addRow(table = $('table#list_table')) {
    Swal.showLoading();
    const {value: new_row_data} = await Swal_md.fire({
        title: tr.__("Aggiungi riga"),
        html: `${tr.__("Dati riga: ")}<br><br>
        <div class="mdc-layout-grid" style="${!$.browser.mobile ? 'display: flex' : ''}">
            <div class="mdc-layout-grid__inner" style="flex: 1">
            ${renderOutlinedSelect("new_row_student", tr.__("Studente"), {
            values: await getStudents(true),
            required: true,
            icon: "mdi-outline-layers",
        })}</div>
            <div class="mdc-layout-grid__inner">
            ${renderOutlinedInput("new_row_date", tr.__("Data"), {
            type: "date",
            icon: "mdi-outline-today",
            width: "max-content"
        })}</div></div>`,
        imageUrl: ROOTDIR + "/app/assets/img/add_row.svg",
        imageAlt: tr.__("Aggiungi riga"),
        imageHeight: 150,
        preConfirm: () => {
            return {
                student_id: selects['new_row_student'].value,
                date: $('#new_row_date').val()
            }
        }
    });
    if (new_row_data) {
        new_row_data.action = "add_row_list";
        new_row_data.code = LIST_CODE;
        request.post(new_row_data, (row_data) => {
            var d = new Date(new_row_data.date);
            table.find('tbody').append(`
        <tr id="list_row_${row_data.id}" class="mdc-data-table__row" style="display: none">
            <td class="mdc-data-table__cell">${row_data.number}</td>
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
            ${(!empty(new_row_data.date)) ? `
                <span id="unix_timestamp" style="display: none">${d.getTime()}</span>
                <span class="date-local">${d.toLocaleDateString()}</span>
            ` : ``}
            </td>
            <td class="mdc-data-table__cell">
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                            title="${tr.__("Modifica")}" onclick="editRow('${row_data.id}')">
                      <i class="mdi-outline-edit mdc-button__icon"></i>
                </button>
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                            title="${tr.__("Elimina")}" onclick="deleteRow('${row_data.id}')">
                      <i class="mdi-outline-delete mdc-button__icon"></i>
                </button>
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon up" title="${tr.__("Su")}">
                    <i class="mdi-outline-keyboard_arrow_up mdc-button__icon"></i>
                </button>
                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon down" title="${tr.__("Giù")}">
                      <i class="mdi-outline-keyboard_arrow_down mdc-button__icon"></i>
                </button>
            </td>
        </tr>`);
            table.find('tbody').find('tr:hidden').fadeIn();
            var no_rows_row = $("#no_rows");
            if (no_rows_row.length) {
                no_rows_row.fadeOut(1000, () => {
                    no_rows_row.remove()
                })
            }
        })
    }
}

async function editRow(row_id) {
    Swal.showLoading();
    var row = $("#list_row_" + row_id);
    var date = "";
    var timestamp = row.find('#unix_timestamp');
    if (timestamp.length !== 0 && !empty(timestamp.text())) {
        var d = new Date(timestamp.text() * 1000); // Convert to milliseconds
        // Convert to format YYYY-MM-DD
        var day = ("0" + d.getDate()).slice(-2);
        var month = ("0" + (d.getMonth() + 1)).slice(-2);
        var date = d.getFullYear() + "-" + (month) + "-" + (day);
    }
    var students = await getStudents(true);
    var students_names = Object.values(students);
    var students_ids = Object.keys(students);
    var name = row.find(".mdc-chip__text").text();
    var student = students_ids[students_names.indexOf(name)];
    if (empty(student)) {
        var username = row.find('.mdc-chip').attr('id');
        student = students_ids[students_names.indexOf(`${name} (${username})`)]
    }
    const {value: row_data} = await Swal_md.fire({
        title: tr.__("Modifica riga"),
        html: `${tr.__("Dati riga: ")}<br><br>
        <div class="mdc-layout-grid" style="${!$.browser.mobile ? 'display: flex' : ''}">
            <div class="mdc-layout-grid__inner" style="flex: 1">
            ${renderOutlinedSelect("row_student", tr.__("Studente"), {
            values: students,
            selected: student,
            required: true,
            icon: "mdi-outline-layers",
        })}</div>
            <div class="mdc-layout-grid__inner">
            ${renderOutlinedInput("row_date", tr.__("Data"), {
            type: "date",
            value: date,
            icon: "mdi-outline-today",
            width: "max-content"
        })}</div></div>`,
        imageUrl: ROOTDIR + "/app/assets/img/edit_row.svg",
        imageAlt: tr.__("Modifica riga"),
        imageHeight: 150,
        preConfirm: () => {
            return {
                student_id: selects['row_student'].value,
                date: $('#row_date').val()
            }
        }
    });
    if (!empty(row_data)) {
        row_data.action = "edit_row_list";
        row_data.code = LIST_CODE;
        row_data.row_id = row_id;
        request.post(row_data, (data) => {
            // Change student
            var chip = row.find('.mdc-chip');
            chip.attr('id', data.student.username);
            //TODO: User image
            chip.find('.mdc-chip__text').text(data.student.name);

            // Change date
            if (!empty(row_data.date)) {
                var date = new Date(row_data.date);
                row.find('.date-local').text(date.toLocaleDateString());
                row.find('#unix_timestamp').text(date.getTime());
            }

            Toast.fire({
                title: tr.__("Riga modificata!"),
                icon: "success"
            })
        })
    }
}

function deleteRow(row_id) {
    var row = $("#list_row_" + row_id);
    Swal_md.fire({
        title: tr.__("Elimina riga"),
        html: tr.__("Sei davvero sicuro di voler eliminare la riga n. %number% (Studente %student%)", {
            '%number%': row.find('td').first().text(),
            '%student%': row.find('.mdc-chip__text').text(),
        }),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: tr.__("Sì, eliminala!"),
        cancelButtonText: tr.__("No, non eliminarla!"),
    }).then((result) => {
        if (result.value) {
            request.post({
                action: "delete_row_list",
                code: LIST_CODE,
                row_id: row_id
            }, (data) => {
                row.fadeOut(1000, () => {
                    row.remove();
                    if (!$('#list_table tbody tr').length) {
                        $('#list_table tbody').append(`
                        <tr id="no_rows" class="mdc-data-table__row" style="display: none">
                            <td class="mdc-data-table__cell" colspan="4" style="text-align: center;">
                                ${tr.__("Nessuna riga nella lista")}
                            </td>
                        </tr>`);
                        $("#no_rows").fadeIn(1000)
                    }
                });

                Toast.fire({
                    title: tr.__("Riga eliminata!"),
                    icon: "success"
                });
            })
        }
    });
}

async function getStudents(format = false) {
    var students;
    return new Promise(resolve => {
        request.post({
            action: "get_classroom_students",
            code: LIST_CODE,
            get_as_list: true
        }, (data) => {
            students = data.students;
            if (format) {
                students = {};
                Object.values(data.students).forEach((value) => {
                    if (!empty(value.username)) {
                        value.name += ` (${value.username})`
                    }
                    students[value.id] = value.name;
                })
            }
            resolve(students)
        });
    });
}

$(document).ready(() => {
    $(".up,.down").click((e) => {
        var row = $(e.target).parents("tr:first");
        var row_id = row.attr('id').replace('list_row_', '');
        var direction;
        if ($(e.target).parent('button').is(".up")) {
            direction = 'up';
            row.insertBefore(row.prev()).hide().fadeIn();
        } else {
            direction = 'down';
            row.insertAfter(row.next()).hide().fadeIn();
        }
        request.post({
            action: "order_row_list",
            code: LIST_CODE,
            row_id: row_id,
            direction: direction
        }, () => {
        });
    });
});