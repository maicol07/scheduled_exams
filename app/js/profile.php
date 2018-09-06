<script>
$(document).ready(function () {
    $('.fixed-action-btn').floatingActionButton();
});

capitalize = function (str1) {
    return str1.charAt(0).toUpperCase() + str1.slice(1);
};

function saveInput(e) {
    if (e === "genere") {
        var mcheck = document.getElementById("male-check");
    } else {
        var input = document.getElementById(e + "-input");
        var i = $("#" + e + "-input");
        var helper = document.getElementById(e + "-helper");
    }
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
            if (this.responseText === "OK") {
                if (e !== "genere") {
                    helper.setAttribute("data-success", "✓");
                    i.removeClass("invalid");
                    i.addClass("valid");
                }
            } else {
                if (e !== "genere") {
                    helper.setAttribute("data-wrong", "✗ <?php echo _("Non riusciamo a salvare quello che stai digitando :(") ?>");
                    i.removeClass("valid");
                    i.addClass("invalid");
                }
            }
        }
    };
    fd = new FormData;
    fd.append("username", username);
    if (e === "aboutme") {
        fd.append("input", "descrizione")
    } else {
        fd.append("input", e);
    }
    if (e === "genere") {
        var isChecked = $('#male-id');
        if (isChecked) {
            fd.append("text", "M");
            console.log("YES")
        } else {
            fd.append("text", "F");
            console.log("NO")
        }
    } else {
        fd.append("text", input.value);
    }
    xmlhttp.open("POST", "includes/save-input.php", true);
    xmlhttp.send(fd);
}

function edit_profile_mode() {
    document.getElementById("fab-icon").innerText = "check";
    var fab_link = document.getElementById("fab-link");
    fab_link.setAttribute("onclick", "window.location=''");
    $("#fab-link").removeClass("red");
    fab_link.className += "light-green";
    var profile_img = document.getElementById("profile-img");
    profile_img.setAttribute("onclick", "change_profile_image('primary')");
    profile_img.className += " activator waves-effect waves-circle waves-light";
    var profile_bg = document.getElementById("profile-bg");
    profile_bg.setAttribute("onclick", "change_profile_image('background')");
    profile_bg.className += " activator";
    document.getElementById("bg-div").className += " waves-effect waves-block waves-light";
    var a = ["nome", "cognome"];
    a.forEach(function (item, index) {
        var div = document.getElementById(item + "-div");
        div.removeAttribute("hidden");
        $("#" + item + "-h4").replaceWith('<input value="' + document.getElementById(item + "-h4").innerText + '" id="' + item + '-input" type="text" class="validate" onkeyup="saveInput(\'' + item + '\')">\n' +
            '          <span id="' + item + '-helper" class="helper-text" data-error="" data-success=""></span>')
    });
    document.getElementById("aboutme").removeAttribute("hidden");
    var aboutme = document.getElementById("aboutme-content");
    $("#aboutme-content").replaceWith('<textarea id="aboutme-input" class="materialize-textarea white-text" onkeyup="saveInput(\'aboutme\')">' + aboutme.innerText + '</textarea>' +
        '<span id="aboutme-helper" class="helper-text" data-error="" data-success=""></span>');
    M.textareaAutoResize($('#aboutme-input'));
    document.getElementById("profile-page-about-details").removeAttribute("hidden");
    document.getElementById("compleanno-li").removeAttribute("hidden");
    document.getElementById("genere-li").removeAttribute("hidden");
    var data = document.getElementById("compleanno-content").innerText;
    $("#compleanno-content").replaceWith('<input value="' + data + '" id="compleanno-input" type="text" class="datepicker"><span id="compleanno-helper" class="helper-text" data-error="" data-success=""></span>');
    var datal = data.split(" ");
    var monthslist = ['<?php echo _("Gen") ?>', '<?php echo _("Feb") ?>', '<?php echo _("Mar") ?>', '<?php echo _("Apr") ?>', '<?php echo _("Mag") ?>', '<?php echo _("Giu") ?>',
        '<?php echo _("Lug") ?>', '<?php echo _("Ago") ?>', '<?php echo _("Set") ?>', '<?php echo _("Ott") ?>', '<?php echo _("Nov") ?>', '<?php echo _("Dic") ?>'];
    $('.datepicker').datepicker({
        format: "dd mmm yyyy",
        yearRange: 50,
        i18n: {
            cancel: '<?php echo _("Annulla") ?>',
            clear: '<?php echo _("Pulisci") ?>',
            done: '<?php echo _("OK") ?>',
            previousMonth: '‹',
            nextMonth: '›',
            months: ['<?php echo _("Gennaio") ?>', '<?php echo _("Febbraio") ?>', '<?php echo _("Marzo") ?>', '<?php echo _("Aprile") ?>',
                '<?php echo _("Maggio") ?>', '<?php echo _("Giugno") ?>', '<?php echo _("Luglio") ?>', '<?php echo _("Agosto") ?>', '<?php echo _("Settembre") ?>',
                '<?php echo _("Ottobre") ?>', '<?php echo _("Novembre") ?>', '<?php echo _("Dicembre") ?>'],
            monthsShort: monthslist,
            weekdays: ['<?php echo _("Domenica") ?>', '<?php echo _("Lunedì") ?>', '<?php echo _("Martedì") ?>', '<?php echo _("Mercoledì") ?>', '<?php echo _("Giovedì") ?>',
                '<?php echo _("Venerdì") ?>', '<?php echo _("Sabato") ?>'],
            weekdaysShort: ['<?php echo _("Dom") ?>', '<?php echo _("Lun") ?>', '<?php echo _("Mart") ?>', '<?php echo _("Mer") ?>', '<?php echo _("Gio") ?>', '<?php echo _("Ven") ?>',
                '<?php echo _("Sab") ?>'],
            weekdaysAbbrev: ['<?php echo _("D") ?>', '<?php echo _("L") ?>', '<?php echo _("M") ?>', '<?php echo _("Me") ?>', '<?php echo _("G") ?>', '<?php echo _("V") ?>',
                '<?php echo _("S") ?>'],
        },
        defaultDate: new Date(datal[2], monthslist.indexOf(datal[1]), datal[0]),
        onClose: function () {
            saveInput("compleanno")
        }
    });
    var genere = document.getElementById("genere-content").innerText;
    if (genere === "Maschio") {
        // noinspection JSDuplicatedDeclaration
        var m = "checked";
        // noinspection JSDuplicatedDeclaration
        var f = "";
    } else {
        // noinspection JSDuplicatedDeclaration
        var m = "";
        // noinspection JSDuplicatedDeclaration
        var f = "checked"
    }
    $("#genere-content").replaceWith('<p><label>' +
        '<input name="genere" id="male-check" class="with-gap" type="radio" onchange="saveInput(\'genere\')" ' + m + '/><span><?php echo _("Maschio") ?></span></label></p><p><label>' +
        '        \'<input name="genere" id="female-check" class="with-gap" type="radio" onchange="saveInput(\'genere\')" ' + f + '/><span><?php echo _("Femmina") ?></span></label></p>')
}

async function change_profile_image(type) {
    if (type === "primary") {
        var title = '<?php echo _("Cambia immagine di profilo") ?>';
        var txt = "<?php echo _("Preferibilmente di dimensioni 256x256px.") ?>";
        var label = '<?php echo _("Carica la tua immagine di profilo") ?>';
        if (document.getElementById("profile-img").getAttribute("src") !== "img/ui/user/male/male.svg" ||
            document.getElementById("profile-img").getAttribute("src") !== "img/ui/user/female/male.svg") {
            var cancel = true;
        } else {
            var cancel = false;
        }
    } else if (type === "background") {
        var title = '<?php echo _("Cambia immagine di sfondo") ?>';
        var txt = "<?php echo _("Preferibilmente alta 250px") ?>.";
        var label = '<?php echo _("Carica la tua immagine di sfondo") ?>';
        if (document.getElementById("profile-bg").getAttribute("src") !== "http://notgoaway.com/wp-content/uploads/2017/07/Background-75.png") {
            var cancel = true;
        } else {
            var cancel = false;
        }
    }
    var confirm = "<?php echo _("Cambia immagine") ?>";
    swal({
        title: "<?php echo _("Quale azione vuoi eseguire?") ?>",
        confirmButtonText: confirm,
        showCancelButton: cancel,
        cancelButtonText: "<?php echo _("Elimina immagine") ?>",
        cancelButtonColor: "red",
        type: "question"
    }).then(async (result) => {
        if (result.dismiss === swal.DismissReason.cancel) {
            swal({
                title: "<?php echo _("Conferma eliminazione") ?>",
                text: "<?php echo _("Si vuole davvero eliminare l'immagine?") ?>",
                type: "warning",
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: "<?php echo _("No") ?>",
                confirmButtonText: "<?php echo _("Sì") ?>",
                confirmButtonColor: "red"
            }).then((result) => {
                if (result.value) {
                    swal.showLoading();

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
                            if (this.responseText === "OK") {
                                if (type === "primary") {
                                    document.getElementById("profile-img").setAttribute("src", "img/ui/user/male/male.svg");
                                    swal({
                                        title: "<?php echo _("Immagine di profilo eliminata!") ?>",
                                        text: "<?php echo _("L'immagine di profilo è stata eliminata! È stata reimpostata quella originale.") ?>",
                                        type: "success",
                                    });
                                } else if (type === "background") {
                                    document.getElementById("profile-bg").setAttribute("src", "http://notgoaway.com/wp-content/uploads/2017/07/Background-75.png");
                                    swal({
                                        title: "<?php echo _("Immagine di sfondo eliminata!") ?>",
                                        text: "<?php echo _("L'immagine di sfondo è stata eliminata! È stata reimpostata quella originale.") ?>",
                                        type: "success",
                                    });
                                }
                            } else {
                                swal({
                                    title: "<?php echo _("Ops! Qualcosa è andato storto!") ?>",
                                    html: "<?php echo _("C'è stato un errore imprevisto durante l'eliminazione dell'immagine:<br><br>") ?>" + this.responseText,
                                    type: "error"
                                })
                            }
                        }
                    };

                    xmlhttp.open("GET", "includes/deleteimage.php?type=" + type + "&user=" + username, true);
                    xmlhttp.send();
                }
            });
        } else {
            const {value: file} = await swal({
                title: title,
                html: txt,
                input: 'file',
                inputAttributes: {
                    'accept': 'image/*',
                    'aria-label': label
                },
                inputClass: "",
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: "<?php echo _("Annulla") ?>"
            });
            if (file) {
                if (!file) throw null;
                swal.showLoading();
                const reader = new FileReader;
                reader.onload = (e) => {
                    const fd = new FormData;
                    fd.append('type', type);
                    fd.append('user', username);
                    fd.append('image', e.target.result);
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
                            if (this.responseText.includes("uploads/")) {
                                if (type === "primary") {
                                    document.getElementById("profile-img").setAttribute("src", this.responseText);
                                    swal({
                                        title: "<?php echo _("Immagine di profilo cambiata!") ?>",
                                        text: "<?php echo _("L'immagine di profilo è stata cambiata!") ?>",
                                        type: "success",
                                    });
                                } else if (type === "background") {
                                    document.getElementById("profile-bg").setAttribute("src", this.responseText);
                                    swal({
                                        title: "<?php echo _("Immagine di sfondo cambiata!") ?>",
                                        text: "<?php echo _("L'immagine di sfondo è stata cambiata!") ?>",
                                        type: "success",
                                    });
                                }
                            } else {
                                swal({
                                    title: "<?php echo _("Errore!") ?>",
                                    html: "<?php echo _("Si è verificato un errore imprevisto durante il caricamento dell'immagine. Riprova più tardi o contatta lo sviluppatore.<br><br>") ?>" + this.responseText,
                                    type: "error"
                                })
                            }
                        }
                    };

                    xmlhttp.open("POST", "includes/uploadimage.php", true);
                    xmlhttp.send(fd);
                };
                reader.readAsDataURL(file)

            }
        }
    })
}
</script>