$(document).ready(function () {
    $('.fixed-action-btn').floatingActionButton();
    var monthslist = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
    $('.tooltipped').tooltip();
});

async function scelta_tipo() {
    await swal({
        title: "Scegli il tipo di generazione",
        html: "Con quali dati vuoi generare la lista " + listname + "?",
        type: "question",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "blue",
        confirmButtonText: "Studenti della classe",
        cancelButtonText: "Materie della classe",
        cancelButtonColor: "green",
        allowOutsideClick: false,
        allowEscapeKey: false,
        focusCancel: true,
        focusConfirm: false
    }).then((result) => {
        if (typeof result.dismiss !== "undefined") {
            if (result.dismiss === swal.DismissReason.cancel) {
                return "subjects";
            }
        } else {
            return "users";
        }
    })
}

function giustificazione(user) {
    var checkbox = $("#giustificazione-" + user);
    var checkbox_span = $("#giustificazione-span-" + user);
    swal.showLoading();
    var errors = [];
    var oggi = new Date();
    if (("#span-" + user).length) {
        var data = document.getElementById("span-" + user).innerText;
    }
    var datainput = $("#data-input-" + user);
    if (datainput.length) {
        if (datainput.val() !== "") {
            var date = new Date(datainput.val());
            var diff = Math.floor((Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) - Date.UTC(oggi.getFullYear(), oggi.getMonth(), oggi.getDate())) / (1000 * 60 * 60 * 24));
            if (diff < 3) {
                errors.push("Non è possibile cambiare lo stato della giustificazione nei 2 giorni precedenti all'interrogazione.")
            }
        }
    } else if (data.length) {
        var date = new Date(data);
        var diff = Math.floor((Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) - Date.UTC(oggi.getFullYear(), oggi.getMonth(), oggi.getDate())) / (1000 * 60 * 60 * 24));
        if (diff < 3) {
            errors.push("Non è possibile cambiare lo stato della giustificazione nei 2 giorni precedenti all'interrogazione.")
        }
    }
    if (errors.length) {
        swal({
            title: "Errore!",
            html: "Si sono verificati degli errori:<br><br>" + errors.join(", "),
            type: "error"
        });
        return
    }
    if (window.XMLHttpRequest) {
        // code for modern browsers
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            swal.close();
            var text = this.responseText.split("\n");
            if (text[0] === "OK") {
                if (checkbox.is(":checked")) {
                    checkbox_span.attr("data-tooltip", "Sei giustificato. Vuoi revocare la giustificazione?");
                } else {
                    checkbox_span.attr("data-tooltip", "Non sei giustificato. Vuoi giustificarti? Giustificazioni rimanenti: " + text[2]);
                }
            } else {
                swal({
                    title: "Errore!",
                    html: "Si è verificato un errore durante il salvataggio della data:<br><br>" + this.responseText,
                    type: "error"
                });
                return
            }
            contenuto = text[1]
        }
    };
    fd = new FormData;
    fd.append("idlista", listid);
    fd.append("user", user);
    if (checkbox.is(":checked")) {
        fd.append("value", "S");
    } else {
        fd.append("value", "N")
    }
    xmlhttp.open("POST", "includes/giustificazione.php", true);
    xmlhttp.send(fd);
}

function saveInput(e) {
    var input = document.getElementById(e + "-input");
    var i = $("#" + e + "-input");
    var helper = document.getElementById(e + "-helper");
    if (window.XMLHttpRequest) {
        // code for modern browsers
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "OK" || this.responseText.includes("uploads/")) {
                helper.setAttribute("data-success", "✓");
                i.removeClass("invalid");
                i.addClass("valid");
            } else {
                helper.setAttribute("data-wrong", "✗ Non riusciamo a salvare quello che stai digitando :(");
                i.removeClass("valid");
                i.addClass("invalid");
            }
        }
    };
    fd = new FormData;
    fd.append("idlista", listid);
    fd.append("input", e);
    if (e === "img") {
        const reader = new FileReader;
        reader.onload = (e) => {
            fd.append("img", e.target.result);
            xmlhttp.open("POST", "includes/save-list-details.php", true);
            xmlhttp.send(fd);
        };
        reader.readAsDataURL($('#img-input')[0].files[0]);

    } else {
        fd.append("text", input.value);
        xmlhttp.open("POST", "includes/save-list-details.php", true);
        xmlhttp.send(fd);
    }
}

/**
 * @return {boolean}
 */
function ValidateEmail(email) {
    return /(.+)@(.+){2,}\.(.+){2,}/.test(email);
}

async function inserimento_manuale() {
    var tipo = scelta_tipo();
    var allemail;
    var emailstr = "";
    while (allemail !== true) {
        var errortext = "";
        if (typeof wrongemail !== "undefined" && wrongemail.length) {
            wrongemail = wrongemail.toString();
            errortext = "<br><br><b style='color: red'>Una o più email sono sbagliate!<br>" + wrongemail + "</b>"
        }
        const {value: email} = await swal({
            title: "Inserisci utenti",
            html: "Inserisci qui le email degli utenti che vuoi aggiungere. <b>Separa ciascuna email una dall'altra con una virgola (,). L'utente DEVE essere registrato e iscritto alla classe per poter essere inserito. In caso contrario NON verrà inserito.</b>" + errortext,
            input: "textarea",
            inputClass: "materialize-textarea",
            inputValue: emailstr,
            showCancelButton: true,
            cancelButtonText: "Annulla",
        });
        if (email) {
            swal.showLoading();
            var emaillist = email.split(",");
            allemail = true;
            var wrongemail = [];
            for (i = 0; i < emaillist.length; i++) {
                if (ValidateEmail(emaillist[i].trim()) === false) {
                    allemail = false;
                    wrongemail.push(emaillist[i].trim());
                }
            }
            emailstr = "";
            emaillist.forEach(function (item, idx) {
                emailstr += (item.trim() + ", ");
            });
        } else {
            return;
        }
    }
    if (window.XMLHttpRequest) {
        // code for modern browsers
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "OK") {
                swal({
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false
                });
                window.location.href = "";
            } else {
                swal({
                    title: "Errore!",
                    html: "Si è verificato un errore, riprovare più tardi o contattare il supporto nella <a href='https://community.interrogazioniprogrammate.tk'>Community</a>.<br><br>" + this.responseText,
                    type: "error"
                })
            }
        }
    };
    fd = new FormData;
    fd.append("idlista", listid);
    fd.append("classid", classid);
    fd.append("email_list", emailstr);
    fd.append("tipo", tipo);
    xmlhttp.open("POST", "includes/manual-list.php", true);
    xmlhttp.send(fd);
}

function generazione_casuale() {
    var tipo = scelta_tipo();
    swal.showLoading();
    if (window.XMLHttpRequest) {
        // code for modern browsers
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "OK") {
                swal({
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false
                });
                window.location.href = "";
            } else {
                swal({
                    title: "Errore!",
                    html: "Si è verificato un errore, riprovare più tardi o contattare il supporto nella <a href='https://community.interrogazioniprogrammate.tk'>Community</a>.<br><br>" + this.responseText,
                    type: "error"
                })
            }
        }
    };
    fd = new FormData;
    fd.append("idlista", listid);
    fd.append("users", classusers);
    fd.append("tipo", tipo);
    xmlhttp.open("POST", "includes/casual-list.php", true);
    xmlhttp.send(fd);
}

function saveDate(user) {
    var input = document.getElementById("data-input-" + user);
    var i = $("#data-input-" + user);
    var helper = document.getElementById("data-helper-" + user);
    if (window.XMLHttpRequest) {
        // code for modern browsers
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var text = this.responseText.split("\n");
            if (text[0] === "OK") {
                helper.setAttribute("data-success", "✓");
                i.removeClass("invalid");
                i.addClass("valid");
            } else {
                swal({
                    title: "Errore!",
                    html: "Si è verificato un errore durante il salvataggio della data:<br><br>" + this.responseText,
                    type: "error"
                });
                helper.setAttribute("data-wrong", "✗ Non riusciamo a salvare quello che stai digitando :(");
                i.removeClass("valid");
                i.addClass("invalid");
                return
            }
            contenuto = text[1]
        }
    };
    fd = new FormData;
    fd.append("idlista", listid);
    fd.append("user", user);
    fd.append("text", input.value);
    xmlhttp.open("POST", "includes/getsavelistcont.php", true);
    xmlhttp.send(fd);
}

async function delete_list() {
    await swal({
        title: "Conferma eliminazione",
        html: "Sei sicuro di voler eliminare la lista " + listname + "?<br>Tutti i dati della lista " +
            "verranno eliminati. <b>Questa opzione è irreversibile!</b>",
        type: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "red",
        confirmButtonText: "Sì",
        cancelButtonText: "Annulla",
        allowOutsideClick: false,
        allowEscapeKey: false,
        focusCancel: true,
        focusConfirm: false
    }).then((result) => {
        if (typeof result.dismiss !== "undefined") {
            if (result.dismiss === swal.DismissReason.cancel) {
                return "cancelled";
            }
        } else {
            swal.showLoading();
            if (window.XMLHttpRequest) {
                // code for modern browsers
                var xmlhttp = new XMLHttpRequest();
            } else {
                // code for old IE browsers
                var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    if (this.responseText === "OK") {
                        window.location.href = "index.php?deletelist=success&deletedlistname=" + listname;
                    } else {
                        swal({
                            title: "Errore",
                            html: "Non è stato possibile eliminare la lista. Riprova in seguito!<br><br>" + this.responseText,
                            type: "error"
                        })
                    }
                }
            };
            var fd = new FormData;
            fd.append("listid", listid);
            xmlhttp.open("POST", "includes/deletelist.php", true);
            xmlhttp.send(fd);
        }
    });
}

function edit_list_mode() {
    document.getElementById("fab-icon").innerText = "check";
    var fab_link = document.getElementById("fab-link");
    fab_link.setAttribute("onclick", "window.location.href = ''");
    $("#fab-link").removeClass("red");
    $("#listname").replaceWith('<div class="input-field"><input id="nome-input" type="text" value="' + document.getElementById("listname").innerText + '" class="validate" onkeyup="saveInput(\'nome\')"><label for="nome-input">Lista</label><span id="nome-helper" class="helper-text" data-error="" data-success=""></span></div>');
    $("#description").replaceWith('<div class="input-field"><textarea id="description-input" class="materialize-textarea" onkeyup="saveInput(\'description\')">' + document.getElementById("description").innerText + '</textarea>\n' +
        '          <label for="description-input">Descrizione</label><span id="description-helper" class="helper-text" data-error="" data-success=""></span></div>');
    M.textareaAutoResize($('#description-input'));
    M.updateTextFields();
    $("#dettagli-lista").append('<div class="row"><p>Immagine: </p><div class="file-field input-field">\n' +
        '      <div class="btn waves-effect waves-light">\n' +
        '        <span>Carica</span>\n' +
        '        <input type="file" id="img-input" accept="image/*">\n' +
        '      </div>\n' +
        '      <div class="file-path-wrapper">\n' +
        '        <input class="file-path validate" type="text" onchange="saveInput(\'img\')">\n' +
        '      <span id="img-helper" class="helper-text" data-error="" data-success=""></span></div>\n' +
        '    </div></div>');
    var users = classusers.split(", ");
    users.forEach(function (user, index) {
        var span = document.getElementById("span-" + user);
        var data = span.innerText;
        $("#span-" + user).replaceWith('<input value="' + data + '" id="data-input-' + user + '" type="text" class="datepicker"><span id="data-helper-' + user + '" class="helper-text" data-error="" data-success=""></span>')
        var datal = data.split(" ");
        var monthslist = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
        $('#data-input-' + user).datepicker({
            format: "yyyy/m/d",
            yearRange: 50,
            i18n: {
                cancel: 'Annulla',
                clear: 'Pulisci',
                done: 'OK',
                previousMonth: '‹',
                nextMonth: '›',
                months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre',
                    'Ottobre', 'Novembre', 'Dicembre'],
                monthsShort: monthslist,
                weekdays: ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                weekdaysAbbrev: ['D', 'L', 'M', 'M', 'G', 'V', 'S'],
            },
            defaultDate: new Date(datal[2], monthslist.indexOf(datal[1]), datal[0]),
            onClose: function () {
                saveDate(user)
            }
        });
    });
    /*     if ($("table").length) {
             $("#maincontainer").append('<a class="btn-flat waves-effect right" id="addmore"><i class="material-icons left">add</i>Aggiungi riga</a>');
             $("#addmore").on('click',function(){
                 count=$('table tr').length;
                 var data="<tr><td>"+count+"</td>";
                 data+='<td><a class="btn-flat waves-effect down"><i class="material-icons">keyboard_arrow_down</i></a></td><td><a class="btn-flat waves-effect up"><i class="material-icons">keyboard_arrow_up</i></a></td><td><a class="btn-flat waves-effect red-text"><i class="material-icons">delete</i></a></td></tr>';
                 $('table').append(data);
             });
             $('table > tbody  > tr').each(function() {
                 $(this).append('<td><a class="btn-flat waves-effect down"><i class="material-icons">keyboard_arrow_down</i></a></td><td><a class="btn-flat waves-effect up"><i class="material-icons">keyboard_arrow_up</i></a></td><td><a class="btn-flat waves-effect red-text"><i class="material-icons">delete</i></a></td>');
             });
             $("table").on("click",".up,.down", function () {
                 var row = $(this).parents("tr:first");
                 if ($(this).is(".up")) {
                     row.insertBefore(row.prev());
                 } else {
                     row.insertAfter(row.next());
                 }
                 // draw the user's attention to it
                 row.fadeOut();
                 row.fadeIn();
             });
         }
         */
}