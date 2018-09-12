<script>
    var SortSelect = function (select) {
        var options = jQuery.makeArray(select.find('option'));

        var sorted = options.sort(function (a, b) {
            return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
        });

        select.append(jQuery(sorted))
            .attr('selectedIndex', 0);
    };

    $(document).ready(function () {
        var select = $('select');
        SortSelect(select);
        select.find("option[value='<?php echo $locale; ?>']").prop("selected", true);
        select.formSelect();
    });

    async function delete_account() {
        const {value: password} = await swal({
            title: "<?php echo _("Confema eliminazione") ?>",
            text: "<?php echo _("Si è davvero sicuri di voler eliminare il proprio account? Per confermare inserire la propria password.") ?>",
            input: 'password',
            inputPlaceholder: '<?php echo _("Inserisci la tua password") ?>',
            inputAttributes: {
                maxlength: 10,
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            type: "warning",
            confirmButtonColor: "red",
            showCancelButton: true,
            cancelButtonText: "<?php echo _("Annulla") ?>"
        });
        if (password) {
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
                            title: "<?php echo _("Account eliminato!") ?>",
                            text: "<?php echo _("Il tuo account è stato eliminato. Premendo OK o rimuovendo questa notifica, tornerai alla schermata di accesso. Ci dispiace che te ne vada :(") ?>",
                            type: "success"
                        }).then((result) => {
                            window.location.href = "../index.php";
                        })
                    } else if (this.responseText === "PSW_ERR") {
                        swal({
                            title: "<?php echo _("Password errata!") ?>",
                            text: "<?php echo _("Il tuo account non è stato eliminato. La password inserita è errata.") ?>",
                            type: "error"
                        });
                    } else {
                        swal({
                            title: "<?php echo _("Errore!") ?>",
                            html: "<?php echo _("Il tuo account non è stato eliminato a causa di un errore.") ?><br><br>" + this.responseText,
                            type: "error"
                        });
                    }
                }
            };
            fd = new FormData;
            fd.append("username", username);
            fd.append("password", password);
            xmlhttp.open("POST", "includes/deleteaccount.php", true);
            xmlhttp.send(fd);
        }
    }

    function saveInput(e) {
        swal.showLoading();
        if (e === "lang") {
            var input = $("select");
        } else {
            var input = $("#" + e + "-input");
            var button = $("#" + e + "-button");
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
                    if (e === "lang") {
                        swal({
                            title: '<?php echo _("Lingua cambiata!") ?>',
                            text: '<?php echo _("La lingua è stata modificata, aggiorna la pagina per utilizzare la lingua selezionata.") ?>',
                            type: 'success'
                        });
                    } else {
                        swal({
                            title: '<?php echo _("Dati cambiati!") ?>',
                            text: '<?php echo _("I tuoi dati sono stati modificati con successo") ?>',
                            type: 'success'
                        });
                        input.prop("disabled", true);
                        button.removeClass("indigo-text");
                        button.addClass("red-text");
                        button.html('<i class="material-icons left">mode_edit</i><?php echo _("Modifica") ?>');
                        button.attr("onclick", "enable_edit('" + e + "')");
                    }
                    if (e === "username") {
                        username = input.val()
                    } else if (e === "password") {
                        $("#oldpassword-input").prop("disabled", true);
                        $("#password-confirm").prop("disabled", true);
                    }
                } else if (this.responseText === "newEmailOK") {
                    swal({
                        title: "<?php echo _("Email cambiata!") ?>",
                        text: "<?php echo _("Manca solo un ultimo passaggio per cambiare la tua email. Ti abbiamo inviato una email (controlla anche nella cartella SPAM o Posta indesiderata) contenente un link per attivare la tua email. Finchè non confermi l'email la tua email attuale non verrà alterata.") ?>",
                        type: "success"
                    });
                    input.prop("disabled", true);
                    button.removeClass("indigo-text");
                    button.addClass("red-text");
                    button.html('<i class="material-icons left">mode_edit</i><?php echo _("Modifica") ?>');
                    button.attr("onclick", "enable_edit('" + e + "')");
                } else if (this.responseText === "PSW_ERR") {
                    swal({
                        title: "<?php echo _("Password errata!") ?>",
                        text: "<?php echo _("La tua password non può essere cambiata. La password attuale inserita è errata.") ?>",
                        type: "error"
                    });
                } else {
                    swal({
                        title: '<?php echo _("Errore!") ?>',
                        html: '<?php echo _("Si è verificato un errore durante il salvataggio dei dati.<br><br>") ?>' + this.responseText,
                        type: "error"
                    })
                }
            }
        };
        fd = new FormData;
        fd.append("username", username);
        fd.append("userID", userID);
        fd.append("input", e);
        fd.append("text", input.val());
        if (e === "password") {
            var pswconfirm = $("#password-confirm");
            if (input.val() === pswconfirm.val()) {
                fd.append("oldpsw", pswconfirm.val());
            } else {
                swal({
                    title: "<?php echo _("Password errata!") ?>",
                    text: "<?php echo _("I campi Nuova Password e Conferma Password non sono uguali.") ?>",
                    type: "error"
                });
                return
            }
        }
        xmlhttp.open("POST", "includes/save-input.php", true);
        xmlhttp.send(fd);
    }

    function enable_edit(component) {
        var input = $("#" + component + "-input");
        input.prop("disabled", false);
        var button = $("#" + component + "-button");
        if (component === "password") {
            $("#oldpassword-input").prop("disabled", false);
            var pswconfirm = $("#" + component + "-confirm");
            pswconfirm.prop("disabled", false);
            button.prop("disabled", true);
            input.on("focusout", function (e) {
                if ($(this).val() !== pswconfirm.val() || $(this).val() === '') {
                    pswconfirm.removeClass("valid").addClass("invalid");
                    button.prop('disabled', true);
                } else {
                    pswconfirm.removeClass("invalid").addClass("valid");
                    button.prop('disabled', false);
                }
            });
            pswconfirm.on("keyup", function (e) {
                if (input.val() !== $(this).val() || $(this).val() === '') {
                    $(this).removeClass("valid").addClass("invalid");
                    button.prop('disabled', true);
                } else {
                    $(this).removeClass("invalid").addClass("valid");
                    button.prop('disabled', false);
                }
            });
        }
        button.removeClass("red-text");
        button.addClass("indigo-text");
        button.html('<i class="material-icons left">check</i> <?php echo _("Conferma") ?>');
        button.attr("onclick", "saveInput('" + component + "')")
    }
</script>