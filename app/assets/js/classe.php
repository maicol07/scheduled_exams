<script>
$(document).ready(function () {
    $('.fixed-action-btn').floatingActionButton();
});

function crea_lista() {
    swal({
        title: "<?php echo _("Crea lista") ?>",
        text: "<?php echo _("Inserisci il nome della lista") ?>",
        input: "text",
        confirmButtonText: "<?php echo _("CREA!") ?>",
        cancelButtonText: "<?php echo _("Annulla") ?>",
    }).then(name => {
        if (!name.value) throw null;

        if (window.XMLHttpRequest) {
            // code for modern browsers
            // noinspection JSDuplicatedDeclaration
            var xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            // noinspection JSDuplicatedDeclaration
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var column = document.createElement("DIV");
                column.className += "col s6 m2 l3";
                var container = document.createElement("DIV"); // Create a <div> element
                container.className += "card activator hoverable waves-effect waves-light divlink"; // Add card class
                container.setAttribute("onclick", "window.location=\"lista.php?id=" + this.responseText + "&nome=" + name.value + "\""); // Add link to div
                var cardcontent = document.createElement("DIV");      // Create a <div> element
                cardcontent.className += "card-action"; // Add CSS class
                var cardspan = document.createElement("SPAN"); //Create <span> element
                cardspan.style.textTransform = "uppercase"; // Add CSS style
                var cardtext = document.createTextNode(name.value); // Create a text node
                cardspan.appendChild(cardtext); // Append the text node to <span>
                cardcontent.appendChild(cardspan); // Append the <span> element to <div>
                container.appendChild(cardcontent); // Append the <div> element to <div>
                column.appendChild(container); // Append <div> element to <div>
                document.getElementById("rigalista").appendChild(column);           // Append <div> to <div> with id="rigaclassi"
                swal({
                    title: "<?php echo _("Lista creata!") ?>",
                    text: "<?php echo _("La lista ") ?>" + name.value + "<?php echo _(" è stata creata!") ?>",
                    type: "success",
                });
            }
        };

        xmlhttp.open("GET", "includes/addlist.php?lista=" + name.value + "&username=" + username + "&classid=" + classid, true);
        xmlhttp.send();

    }).catch(err => {
        if (err) {
            swal("<?php echo _("Oh no!") ?>", "<?php echo _("Si è verificato un errore!") ?>", "error");
        } else {
            swal.disableLoading();
            swal.close();
        }
    });
}

function show_class(users) {
    var userslist = users.split(", ");
    var usertext = "";
    userslist.forEach(function (user) {
        if (window.XMLHttpRequest) {
            // code for modern browsers
            // noinspection JSDuplicatedDeclaration
            var xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            // noinspection JSDuplicatedDeclaration
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var info = this.responseText.split(", ");
                var name = info[0] + " " + info[1];
                if (name === "" || name.includes("null")) {
                    var name = user;
                }
                if (info[3] === "" || info[3] === "null") {
                    if (info[2] === "F") {
                        var img = "img/user/female.svg";
                    } else {
                        var img = "img/user/male.svg"
                    }
                } else {
                    var img = info[3]
                }
                usertext += '<div class="chip hoverable" style="cursor: pointer;" onclick="window.open(\'profile.php?user=' + user + '\', \'_blank\');"><img src="' + img + '" alt="' + name + '">' + name + '</div>';
                swal({
                    title: "<?php echo _("Partecipanti") ?>",
                    html: usertext
                })
            }
        };

        xmlhttp.open("GET", "includes/getuserdata.php?username=" + user, true);
        xmlhttp.send();

    });
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
                helper.setAttribute("data-wrong", "✗ <?php echo _("Non riusciamo a salvare quello che stai digitando :(") ?>");
                i.removeClass("valid");
                i.addClass("invalid");
            }
        }
    };
    fd = new FormData;
    fd.append("idclasse", classid);
    fd.append("input", e);
    if (e === "img") {
        const reader = new FileReader;
        reader.onload = (e) => {
            fd.append("img", e.target.result);
            xmlhttp.open("POST", "includes/save-class-details.php", true);
            xmlhttp.send(fd);
        };
        reader.readAsDataURL($('#img-input')[0].files[0]);

    } else {
        fd.append("text", input.value);
        xmlhttp.open("POST", "includes/save-class-details.php", true);
        xmlhttp.send(fd);
    }

}

/**
 * @return {boolean}
 */
function ValidateEmail(email) {
    return /(.+)@(.+){2,}\.(.+){2,}/.test(email);
}

async function invite_users() {
    var allemail;
    var emailstr = "";
    while (allemail !== true) {
        var errortext = "";
        if (typeof wrongemail !== "undefined" && wrongemail.length) {
            wrongemail = wrongemail.toString();
            errortext = "<br><br><b style='color: red'><?php echo _("Una o più email sono sbagliate!<br>") ?>" + wrongemail + "</b>"
        }
        const {value: email} = await swal({
            title: "<?php echo _("Invita utenti") ?>",
            html: "<?php echo _("Inserisci qui le email degli utenti che vuoi invitare. <b>Separa ciascuna email una dall'altra con una virgola (,).</b> Se un utente non è registrato, gli invieremo un invito per registrarsi.") ?>" + errortext,
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
                    title: "<?php echo _("Utenti invitati") ?>",
                    text: "<?php echo _("Abbiamo invitato gli utenti ad entrare nella classe :)") ?>",
                    type: "success"
                })
            } else {
                swal({
                    title: "<?php echo _("Errore") ?>",
                    html: "<?php echo _("Non è stato possibile invitare la classe. Riprova in seguito!<br><br>") ?>" + this.responseText,
                    type: "error"
                })
            }
        }
    };
    var fd = new FormData;
    fd.append("classid", classid);
    fd.append("classname", classname);
    fd.append("email_list", emailstr);
    fd.append("user", username);
    xmlhttp.open("POST", "includes/invite-users.php", true);
    xmlhttp.send(fd);
}

async function delete_class() {
    await swal({
        title: "<?php echo _("Conferma eliminazione") ?>",
        html: "<?php echo _("Sei sicuro di voler eliminare la classe ") ?>" + classname + "<?php echo _("?<br>Tutti i dati e le liste della classe ") ?>" +
            "<?php echo _("verranno eliminati. <b>Questa opzione è irreversibile!</b>") ?>",
        type: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "red",
        confirmButtonText: "<?php echo _("Sì") ?>",
        cancelButtonText: "<?php echo _("Annulla") ?>",
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
                        window.location.href = "index.php?deleteclass=success&nome=" + classname;
                    } else {
                        swal({
                            title: "<?php echo _("Errore") ?>",
                            html: "<?php echo _("Non è stato possibile eliminare la classe. Riprova in seguito!") ?><br><br>" + this.responseText,
                            type: "error"
                        })
                    }
                }
            };
            var fd = new FormData;
            fd.append("classid", classid);
            xmlhttp.open("POST", "includes/deleteclass.php", true);
            xmlhttp.send(fd);
        }
    });
}

function edit_class_mode() {
    document.getElementById("fab-icon").innerText = "check";
    var fab_link = document.getElementById("fab-link");
    fab_link.setAttribute("onclick", "window.location=''");
    $("#fab-link").removeClass("red");
    $("#classname").replaceWith('<div class="input-field"><input id="nome-input" type="text" value="' + document.getElementById("classname").innerText + '" class="validate" onkeyup="saveInput(\'nome\')"><label for="nome-input"><?php echo _("Classe") ?></label><span id="nome-helper" class="helper-text" data-error="" data-success=""></span></div>');
    $("#description").replaceWith('<div class="input-field"><textarea id="description-input" class="materialize-textarea" onkeyup="saveInput(\'description\')">' + document.getElementById("description").innerText + '</textarea>\n' +
        '          <label for="description-input"><?php echo _("Descrizione") ?></label><span id="description-helper" class="helper-text" data-error="" data-success=""></span></div>');
    M.textareaAutoResize($('#description-input'));
    M.updateTextFields();
    $("#dettagli-classe").append('<div class="row"><p>Immagine: </p><div class="file-field input-field">\n' +
        '      <div class="btn waves-effect waves-light">\n' +
        '        <i class="material-icons left">cloud_upload</i>' +
        '<span><?php echo _("Carica") ?></span>\n' +
        '        <input type="file" id="img-input" accept="image/*">\n' +
        '      </div>\n' +
        '      <div class="file-path-wrapper">\n' +
        '        <input class="file-path validate" type="text" onchange="saveInput(\'img\')">\n' +
        '      <span id="img-helper" class="helper-text" data-error="" data-success=""></span></div>\n' +
        '    </div></div>')
}
</script>