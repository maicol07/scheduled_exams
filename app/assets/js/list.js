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
}

async function createList() {
    const {value: list_data} = await Swal_md.fire({
        title: tr.__("Crea lista"),
        html: `${renderOutlinedInput('list_name_input', tr.__("Nome lista"), {
            required: true,
            icon: "mdi-outline-format_list_bulleted",
            width: "400px"
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
            width: "400px"
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
                            icon: "mdi-outline-today",
                            width: "400px"
                        })}<br>
                            <br>
                            ${tr.__("Giorni in cui si effettua l'interrogazione:")}<br>
                            <br>
                            ${renderChipset("exam_days", {
                            filter: true,
                            chips: chips
                        })}<br>
                            ${renderOutlinedInput('student_num', tr.__("Numero di studenti interrogati per volta"), {
                            icon: 'mdi-outline-people_outline',
                            width: "400px"
                        })}
                        </div>`);
                        $("#start_date_options").fadeIn(1000);
                        var input = $("#start_date_selection");
                        initInput(input.parent());
                        input.focus(() => {
                            dp.show(new Date(), (date) => {
                                inputs['start_date_selection'].value = date.toLocaleDateString()
                            })
                        });

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
                data.start_date = $("#start_date_selection").val();
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
        list_data.action = 'create_list';
        list_data.classroom_code = CLASSROOM_CODE;
        request.post(list_data, (data) => {
            addListToGrid(data);
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
        html: tr.__("Non sarà poi possibile ripristinarla! Perderai, inoltre, tutte le liste e le informazioni associate " +
            "alla lista!<br>Sei sicuro di voler continuare?"),
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
                var div = $(`#classroom_${data.code}`);
                div.fadeOut(1000, () => {
                    div.remove()
                });
                Toast.fire({
                    title: tr.__("Lista eliminata!"),
                    icon: "success"
                });
                if (CLASSROOM_CODE) {
                    window.location.replace(BASEURL + '/app/classroom?view=' + CLASSROOM_CODE)
                }
            })
        }
    });
}

function shareList(code) {
    Swal_md.fire({
        title: tr.__("Condividi la lista"),
        html: `${tr.__("Il codice della lista è :code:.<br>Il seguente indirizzo pubblico, invece, è l'indirizzo a cui sarà " +
            "possibile visiualizzare la lista (<b>Attenzione! Chiunque possieda il link può visualizzare le informazioni " +
            "contenuti in essa!</b>): :link:", {
            ':code:': `<pre>${code}</pre>`, ':link:': `<br><br><code>
<a href="${BASEURL}/list?view=${code}">${BASEURL}/list?view=${code}</a></code>`
        })}`
    });
}

// List page
function editList() {
    var card = $('#class_info');
    var img = card.find('.mdc-card__media');

    // Image upload
    img.append(`
                <div class="hvr-img">
                    <input type="file" name="class_img" class="upload" id="image_input" accept="image/*">
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
                html: tr.__("Non hai inserito nessun file oppure il file inserito non è un file immagine (deve avere una fra le seguenti estensioni:<br> " +
                    "<code>.jpe, .jpg, .jpeg, .gif, .png, .bmp, .ico, .svg, .svgz, .tif, .tiff, .ai, .drw, .pct, .psp, .xcf, .psd, .raw</code>"),
                icon: "error"
            })
        }
    });

    // Class name
    var name = card.find('.mdc-card__primary div.mdc-typography--headline6');
    name.replaceWith(renderOutlinedInput("class_name", tr.__("Nome classe"), name.text()));

    // Class description
    var description = card.find('.mdc-card__secondary div.mdc-typography--subtitle2');
    description.replaceWith(renderOutlinedInput("class_description", tr.__("Descrizione classe"), description.text(), true));
    initInput($('input#class_name, textarea#class_description').parent('.mdc-text-field'));

    // Edit button
    var button = card.find('.mdc-card__actions button#edit_button');
    button.attr("title", tr.__("Salva")).attr("onclick", "saveClassroom()").attr('id', 'save_button');
    button.find('i.mdc-button__icon').removeClass().addClass('mdc-button__icon mdi-outline-save')
}

function saveList() {
    request.post({
        action: 'update_classroom',
        code: CLASSROOM_CODE,
        name: $('input#class_name').val(),
        description: $('textarea#class_description').val(),
        image: $('#class_info .mdc-card__media').css('background-image').slice(4, -1).replace(/"/g, "")
    }, () => {
        var card = $('#class_info');
        var img = card.find('.mdc-card__media');

        // Image
        img.find('div.hvr-img').remove();
        img.find('i').remove();
        card.find('.mdc-card__primary-action').off('click');

        // Class name
        var name = $('input#class_name');
        name.parent('div.mdc-text-field').replaceWith(`<div class="mdc-typography--headline6">${name.val()}</div>`);

        // Class description
        var description = $('textarea#class_description');
        description.parent('div.mdc-text-field').replaceWith(`<div class="mdc-typography--subtitle2">${description.val()}</div>`);

        // Edit button
        var button = card.find('.mdc-card__actions button#save_button');
        button.attr("title", tr.__("Modifica")).attr("onclick", "editClassroom()").attr('id', 'edit_button');
        button.find('i.mdc-button__icon').removeClass().addClass('mdc-button__icon mdi-outline-edit');

        Toast.fire({
            title: tr.__("Classe modificata!"),
            icon: 'success'
        })
    })
}