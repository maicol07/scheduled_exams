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
                    <div class="mdc-card__primary-action" tabindex="0" onclick="window.location.href = BASEURL + 'app/classroom?view=${data.code}'">
                        <div class="mdc-card__primary">
                            <h2 class="mdc-typography--headline6">${data.name}</h2>
                        </div>
                        <div class="mdc-card__secondary mdc-typography--body2">
                            <small>${tr.__("Codice classe: %s", data.code)}</small>
                        </div>
                    </div>
                    <div class="mdc-card__actions">
                        <div class="mdc-card__action-buttons">
                            <a href="class?view=${data.code}" class="mdc-button mdc-card__action mdc-card__action--button">
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
        html: tr.__("Inserire il nome della classe:") + renderSwalInput('classroom_name_input', tr.__("Nome classe")),
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
            Swal.fire({
                title: tr.__("Classe creata!"),
                text: tr.__("La classe è stata creata! Puoi ora chiudere questo messaggio"),
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
                Swal_md.fire({
                    title: tr.__("Classe eliminata!"),
                    text: tr.__("La classe è stata eliminata con successo!"),
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

function joinClassroom() {
    request.post({
        action: "join_classroom",
        code: $("#classroom_join_code").val()
    }, (data) => {
        addClassroomToList(data);
        Swal_md.fire({
            title: tr.__("Ti sei unito alla classe %s", data.name),
            text: tr.__("Puoi ora chiudere questa finestra"),
            icon: "success"
        })
    })
}