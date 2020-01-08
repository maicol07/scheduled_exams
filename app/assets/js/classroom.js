// Global constants
const CLASSROOM_CODE = get('view');

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
    initRipple($(`#classroom_${data.code}`).find('div.mdc-card .mdc-card__primary-action'));
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
            initSwalBtn() // This onRender() replaces the default one
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
        html: tr.__("Non sarà poi possibile ripristinarla! Perderai, inoltre, tutte le liste e le informazioni associate " +
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
                if (CLASSROOM_CODE) {
                    window.location.replace(BASEURL + '/app/')
                }
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
function studentsList() {
    Swal.showLoading();
    request.post({
        action: 'get_classroom_students',
        code: CLASSROOM_CODE
    }, (data) => {
        var html;
        var image = '';

        function buildStudentsList() {
            html = `<ul class="mdc-list mdc-list--two-line">`;
            data.students.forEach((student) => {
                var link_btn = student.username ? `
                    <button class="mdc-icon-button" title="${tr.__("Scollega da questo utente")}" onclick="unlinkStudent(${student.id}, '${student.name}')">
                        <i class="mdc-button__icon mdi-outline-link_off"></i>
                    </button>` : `<button class="mdc-icon-button" title="${tr.__("Collega ad un utente...")}" onclick="linkStudent(${student.id}, '${student.name}')">
                                    <i class="mdc-button__icon mdi-outline-link"></i>
                                </button>`;
                html += `
                       <li class="mdc-list-item">
                            <span class="mdc-list-item__graphic"><img src="${student.image}" alt="${student.name}"></span>
                            <span class="mdc-list-item__text">
                                <span class="mdc-list-item__primary-text">${student.name}</span>
                                ${student.username ? `<span class="mdc-list-item__secondary-text">${student.username}</span>` : ''}
                            </span>
                            <span class="mdc-list-item__meta">
                                ${link_btn}
                                <button class="mdc-icon-button" title="${tr.__("Modifica")}" onclick="editStudent(${student.id}, '${student.name}')">
                                    <i class="mdc-button__icon mdi-outline-edit"></i>
                                </button>
                                <button class="mdc-icon-button" title="${tr.__("Elimina")}" onclick="removeStudent(${student.id}, '${student.name}')">
                                    <i class="mdc-button__icon mdi-outline-delete"></i>
                                </button>
                            </span>
                        </li>`
            });
            html += `</ul>`;
            return html
        }

        if (data.students) {
            html = buildStudentsList();
        } else {
            html = tr.__("Non è presente alcun studente. Vuoi aggiungerne uno?");
            image = ROOTDIR + '/app/assets/img/undraw/empty.svg';
        }
        Swal_md.fire({
            title: (data.students != null) ? tr.__("Lista studenti") : tr.__("Nessuno studente"),
            html: html,
            imageUrl: image,
            imageAlt: tr.__("No data"),
            imageHeight: 150,
            confirmButtonText: `<i class="mdi-outline-add mdc-button__icon"></i><span class="mdc-button__label">${tr.__("Aggiungi")}</span>`,
            showCancelButton: true,
            cancelButtonText: tr.__("Chiudi"),
            onRender: () => {
                initList($('.swal2-content ul.mdc-list'));
                initRipple($('.swal2-content ul.mdc-list li.mdc-list-item span.mdc-list-item__graphic button.mdc-icon-button'));
                initSwalBtn() // This onRender() replaces the default one
            }
        }).then(async (result) => {
            if (result.value) {
                const {value: student_name} = await Swal_md.fire({
                    title: tr.__("Aggiungi studente"),
                    html: tr.__("Inserire il nome e cognome dello studente:") + renderOutlinedInput('student_name_input', tr.__("Nome studente")),
                    imageUrl: ROOTDIR + "/app/assets/img/plus.svg",
                    imageAlt: tr.__("Aggiungi studente"),
                    imageHeight: 150,
                    preConfirm: () => {
                        return $('#student_name_input').val()
                    }
                });
                if (student_name) {
                    Swal.showLoading();
                    request.post({
                        action: "add_classroom_student",
                        name: student_name,
                        code: CLASSROOM_CODE
                    }, (data) => {
                        Toast.fire({
                            title: tr.__("Studente aggiunto!"),
                            icon: "success"
                        });
                        studentsList()
                    })
                }
            }
        });
    });
}

async function editStudent(id, name) {
    const {value: student_name} = await Swal_md.fire({
        title: tr.__("Modifica studente"),
        html: tr.__("Inserire il nome e cognome dello studente:") + renderOutlinedInput('student_name_input', tr.__("Nome studente"), name),
        imageUrl: ROOTDIR + "/app/assets/img/edit.svg",
        imageAlt: tr.__("Modifica studente"),
        imageHeight: 150,
        preConfirm: () => {
            return $('#student_name_input').val()
        }
    });
    if (student_name) {
        request.post({
            action: 'edit_classroom_student',
            student_id: id,
            student_name: student_name,
            code: CLASSROOM_CODE
        }, (data) => {
            Toast.fire({
                title: tr.__("Studente modificato!"),
                icon: "success"
            });
            studentsList()
        })
    }
}

function linkStudent(id, name) {
    request.post({
        action: 'get_classroom_users',
        code: CLASSROOM_CODE
    }, async (data) => {
        /*//TODO 1.1: See user name, surname and username
        var values = {};
        Object.keys(data.users).forEach((user) => {
            values[user] = data.users[user]
        });*/
        var values = data.users;
        const {value: selected_user} = await Swal_md.fire({
            title: tr.__("Collegamento studente a utente"),
            html: `${tr.__("Scegliere a quale utente collegare lo studente %s:", name)}<br><br>
                    ${renderOutlinedSelect('user_select', tr.__("Utente"), values, null, true, 'mdi-outline-person')}`,
            preConfirm: () => {
                return window.selects["user_select"].value
            }
        });
        if (selected_user) {
            request.post({
                action: 'link_classroom_student',
                code: CLASSROOM_CODE,
                student_id: id,
                user_id: selected_user
            }, (data) => {
                Toast.fire({
                    title: tr.__("Studente :student_name: collegato a :user_name:!", {
                        ':student_name:': name,
                        ':user_name:': values[selected_user]
                    }),
                    icon: 'success'
                });
                studentsList();
            })
        }
    })
}

function unlinkStudent(id, name) {
    Swal_md.fire({
        title: tr.__("Attenzione!"),
        html: tr.__("Si è sicuri di voler scollegare lo studente %s dal suo utente?", name),
        icon: "warning",
        confirmButtonText: tr.__("Sì"),
        showCancelButton: true,
        cancelButtonText: tr.__("No")
    }).then((result) => {
        if (result.value) {
            request.post({
                action: "unlink_classroom_student",
                code: CLASSROOM_CODE,
                student_id: id
            }, (data) => {
                Toast.fire({
                    title: tr.__("Studente %s scollegato dal suo utente!", name),
                    icon: "success"
                });
                studentsList();
            })
        }
    })
}

function removeStudent(id, name) {
    Swal_md.fire({
        title: tr.__("Attenzione!"),
        text: tr.__(`Si vuole davvero eliminare lo studente ${name}?`),
        icon: "warning",
        confirmButtonText: tr.__("Sì"),
        showCancelButton: true,
        cancelButtonText: tr.__("No")
    }).then((result) => {
        if (result.value) {
            request.post({
                action: 'delete_classroom_student',
                student_id: id,
                code: CLASSROOM_CODE
            }, (data) => {
                Toast.fire({
                    title: tr.__("Studente eliminato!"),
                    icon: "success"
                });
                studentsList()
            })
        }
    })
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

function leaveClassroom(id, name) {
    Swal_md.fire({
        title: tr.__("Sei sicuro di voler abbandonare la classe %s?", name),
        html: tr.__("Potrai nuovamente rientrare nella classe utilizzando il codice della classe"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: tr.__("Sì, abbandono la classe!"),
        cancelButtonText: tr.__("No, ho sbagliato!"),
    }).then((result) => {
        if (result.value) {
            request.post({
                action: "leave_classroom",
                id: id
            }, (data) => {
                var div = $(`#classroom_${data.code}`);
                if (div) {
                    div.fadeOut(1000, () => {
                        div.remove()
                    });
                }
                Toast.fire({
                    title: tr.__("Classe abbandonata!"),
                    icon: "success"
                });
                if (CLASSROOM_CODE) {
                    window.location.replace(BASEURL + '/app/')
                }
            })
        }
    });
}