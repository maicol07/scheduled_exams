function addClassroomToList(data) {
    var inner;
    var grid = $('.mdc-layout-grid__inner');
    if (grid.length) {
        inner = grid.append(`<div class="mdc-layout-grid__cell" id="classroom_${data.code}"></div>`).find(`#classroom_${data.code}`)
    } else {
        inner = $('.mdc-layout-grid').append(`<div class="mdc-layout-grid__inner"><div class="mdc-layout-grid__cell" id="classroom_${data.code}></div></div>`)
            .find('.mdc-layout-grid__cell').first()
    }
    inner.append(`<div class="mdc-card" style="display: none">
                    <div class="mdc-card__primary-action" tabindex="0" onclick="window.location.href = BASEURL + '/app/classroom?view=${data.code}'">
                        <div class="mdc-card__primary">
                            <h2 class="mdc-typography--headline6">${data.name}</h2>
                        </div>
                        <div class="mdc-card__secondary mdc-typography--body2">
                            <small>${tr.__("Codice classe: %s", data.code)}</small>
                        </div>
                    </div>
                    <div class="mdc-card__actions">
                        <div class="mdc-card__action-buttons">
                            <a href="classroom?view=${data.code}" class="mdc-button mdc-card__action mdc-card__action--button">
                                <div class="mdc-button__ripple"></div>
                                <i class="mdi-outline-open_in_new mdc-button__icon"></i>
                                <span class="mdc-button__label">${tr.__("Apri")}</span>
                            </a>
                        </div>
                        <div class="mdc-card__action-icons">
                            <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                    title="${tr.__("Condividi")}" onclick="shareClassroom('${data.code}')">
                              <i class="mdi-outline-share mdc-button__icon"></i>
                            </button>
                            <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                    title="${tr.__("Elimina")}"
                                    onclick="deleteClassroom('${data.id}', '${data.name}')">
                              <i class="mdi-outline-delete mdc-button__icon"></i>
                            </button>
                        </div>
                    </div>
                </div>`);
    inner.find('.mdc-card:hidden').fadeIn(1000);
    var no_classrooms_div = $('#noclassrooms');
    no_classrooms_div.fadeOut(1000, () => {
        no_classrooms_div.remove()
    });
}

async function createClassroom() {
    const {value: classroom_name} = await Swal_md.fire({
        title: tr.__("Crea classe"),
        html: tr.__("Inserire il nome della classe:") + renderOutlinedInput('classroom_name_input', tr.__("Nome classe")),
        imageUrl: ROOTDIR + "/app/assets/img/plus.svg",
        imageAlt: tr.__("Crea classe"),
        imageHeight: 150,
        onRender: () => {
            initInput($('#classroom_name_input').parent());
            initSwalBtn();
        },
        preConfirm: () => {
            return $('#classroom_name_input').val()
        }
    });
    if (classroom_name) {
        request.post({
            action: "create_classroom",
            name: classroom_name
        }, (data) => {
            addClassroomToList(data);
            Toast.fire({
                title: tr.__("Classe creata!"),
                icon: "success"
            });
        })
    }
}

function deleteClassroom(id, name) {
    Swal_md.fire({
        title: tr.__("Sei sicuro di voler eliminare la classe %s?", name),
        html: tr.__("Non sarà poi possibile ripristinarla! Perderai, inoltre, tutte le liste e le informazioni associate" +
            "alla classe!<br>Sei sicuro di voler continuare?"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: tr.__("Sì, eliminala!"),
        cancelButtonText: tr.__("No, non eliminarla!"),
    }).then((result) => {
        if (result.value) {
            request.post({
                action: "delete_classroom",
                id: id
            }, (data) => {
                var div = $(`#classroom_${data.code}`);
                div.fadeOut(1000, () => {
                    div.remove()
                });
                Toast.fire({
                    title: tr.__("Classe eliminata!"),
                    icon: "success"
                });
            })
        }
    });
}

function shareClassroom(code) {
    Swal_md.fire({
        title: tr.__("Condividi la classe"),
        html: `${tr.__("Il codice per entrare nella classe è %sIl codice è da inserire nella dashboard, " +
            "premendo sul pulsante <b>Unisciti ad una classe</b>", `<pre>${code}</pre>`)}`
    });
}

$('#join_classroom').submit((event) => {
    event.preventDefault();
    request.post({
        action: "join_classroom",
        code: $("#classroom_join_code").val()
    }, (data) => {
        addClassroomToList(data);
        Toast.fire({
            title: tr.__("Ti sei unito alla classe %s", data.name),
            icon: "success"
        })
    })
});

// Classroom page
function usersList() {
    request.post({
        action: 'get_classroom_users'
    });
    Swal_md.fire({
        title: __("Lista utenti"),
        html: __()
    })
}

function studentsList() {

}

function editClassroom() {
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

function saveClassroom() {
    request.post({
        action: 'update_classroom',
        code: get('view'),
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