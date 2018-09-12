<?php /** @noinspection ALL */
$title = "Impostazioni";
$inc_script = "settings";
$filename = "settings.php";
require_once("layout/header.php");
?>
    <!-- START NAVBAR -->
<?php include("layout/navbar.php"); ?>
    <!-- END NAVBAR -->
    <!-- START BODY -->
    <style>
        tbody > tr {
            border-bottom: none;
        }

        input {
            width: auto !important;
        }

        .outlined {
            border: 1px solid #bdbdbd;
            border-radius: 6px;
        }
    </style>
    <div class="container">
        <h2><?php echo _("Impostazioni") ?></h2>
        <div class="card-panel">
            <table class="striped responsive-table">
                <thead>
                <tr>
                    <th><?php echo _("Parametro") ?></th>
                    <th class="right-align"><?php echo _("Azioni") ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="input-field">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="username-input" type="text" class="validate"
                                   value='<?php echo $userinfo["username"] ?>'
                                   disabled minlength="4">
                            <label for="username-input"><?php echo _("Nome utente") ?></label>
                        </div>
                    </td>
                    <td class="right-align">
                        <a class="btn-flat outlined waves-effect red-text" id="username-button"
                           onclick="enable_edit('username')"><i
                                    class="material-icons left">mode_edit</i><?php echo _("Modifica") ?></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-field">
                            <i class="material-icons prefix">email</i>
                            <input id="email-input" type="email" class="validate"
                                   value='<?php echo $userinfo["email"] ?>'
                                   disabled>
                            <label for="email-input"><?php echo _("Email") ?></label>
                        </div>
                    </td>
                    <td class="right-align">
                        <a class="btn-flat outlined waves-effect red-text" id="email-button"
                           onclick="enable_edit('email')"><i
                                    class="material-icons left">mode_edit</i><?php echo _("Modifica") ?></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row">
                            <div class="input-field col">
                                <i class="material-icons prefix">vpn_key</i>
                                <input id="oldpassword-input" type="password" class="validate" disabled minlength="8">
                                <label for="oldpassword-input"><?php echo _("Password attuale") ?></label>
                            </div>
                            <div class="input-field col">
                                <input id="password-input" type="password" class="validate" disabled minlength="8">
                                <label for="password-input"><?php echo _("Password") ?></label>
                            </div>
                            <div class="input-field col">
                                <input id="password-confirm" type="password" class="validate" disabled minlength="8">
                                <label for="password-confirm"><?php echo _("Conferma password") ?></label>
                            </div>
                        </div>
                    </td>
                    <td class="right-align">
                        <a class="btn-flat outlined waves-effect red-text" id="password-button"
                           onclick="enable_edit('password')"><i
                                    class="material-icons left">update</i><?php echo _("Cambia") ?></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <h5><?php echo _("Altre impostazioni") ?></h5>
            <div class="row" style="padding-top: 10px">
                <div class="col s12 m8">
                    <div class="input-field col">
                        <i class="material-icons prefix">devices</i>
                        <input id="registerIP" type="text" value='<?php echo $userinfo["registerIP"] ?>' disabled>
                        <label for="registerIP"><?php echo _("Indirizzo IP registrazione") ?></label>
                    </div>
                    <div class="input-field col">
                        <input id="lastloginIP" type="text" value='<?php echo $userinfo["lastloginIP"] ?>' disabled>
                        <label for="lastloginIP"><?php echo _("Indirizzo IP ultimo accesso") ?></label>
                    </div>
                    <div class="input-field col">
                        <i class="material-icons prefix">translate</i>
                        <select id="lang-input" onchange="saveInput('lang')">
                            <option id="fr_FR" value="fr_FR"
                                    data-icon="../img/flags/fr_FR.svg"><?php echo _("Francese") ?></option>
                            <option id="en_US" value="en_US"
                                    data-icon="../img/flags/en_US.svg"><?php echo _("Inglese") ?></option>
                            <option id="it_IT" value="it_IT"
                                    data-icon="../img/flags/it_IT.svg"><?php echo _("Italiano") ?></option>
                        </select>
                        <label><?php echo _("Lingua predefinita") ?></label>
                    </div>
                </div>
                <div class="col s12 m4 right-align">
                    <a class="btn waves-effect waves-light red" id="delete-button" onclick="delete_account()"><i
                                class="material-icons left">delete</i><?php echo _("Elimina account") ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Body -->
<?php
include("layout/footer.php")
?>