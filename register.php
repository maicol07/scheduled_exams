<?php /** @noinspection PhpUnhandledExceptionInspection */
require('includes/config.php');
// se l'utente ha già eseguito l'accesso reindirizzalo alla sua pagina
if ($user->is_logged_in()) {
    header('Location: app/index.php');
}
// se il form è stato inviato, processalo
if (isset($_POST['submit'])) {

    // Validazione aggiuntiva (in caso di alcuni bug o trucchetti)
    if (strlen($_POST['username']) < 4) {
        $error[] = _('Il nome utente inserito è troppo corto!');
    } else {
        $stmt = $db->prepare('SELECT username FROM users WHERE username = :username');
        $stmt->execute(array(':username' => $_POST['username']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['username'])) {
            $error[] = _('Il nome utente inserito è già in uso. Sceglierne un altro.');
        }

    }

    if (strlen($_POST['password']) < 8) {
        $error[] = _('La password inserita è troppo corta.');
    }

    if (strlen($_POST['confirm-password']) < 8) {
        $error[] = _('La conferma della password inserita è troppo corta.');
    }

    if ($_POST['password'] != $_POST['confirm-password']) {
        $error[] = _('Le password inserite non corrispondono.');
    }

    // VALIDAZIONE EMAIL
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = _("L'email inserita non è valida. Perfavore inserirne una corretta.");
    } else {
        $stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute(array(':email' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['email'])) {
            $error[] = _("L'email inserita è già utilizzata.");
        }

    }


    // Se non è stato riscontrato nessun errore si procede
    if (!isset($error)) {

        // crea una stringa hash per la password
        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

        // crea il codice di attivazione
        $activasion = md5(uniqid(rand(), true));

        try {
            // Inserisci nel database con un comando preparato
            $stmt = $db->prepare('INSERT INTO users (username,password,email,active,registerIP) VALUES (:username, :password, :email, :active, :ip)');
            $stmt->execute(array(
                ':username' => $_POST['username'],
                ':password' => $hashedpassword,
                ':email' => $_POST['email'],
                ':active' => $activasion,
                ':ip' => get_user_ip()
            ));
            $id = $db->lastInsertId('userID');

            //send email
            $to = $_POST['email'];
            $subject = _("Conferma della registrazione") . " - " . _("Interrogazioni Programmate");
            /** @noinspection JSUnusedGlobalSymbols */
            $body = "<style>
@import url('https://fonts.googleapis.com/css?family=Black+Ops+One');
.logo-text {
    font-family: 'Black Ops One', cursive !important;
    font-variant: small-caps !important;
    color: #003471;
}
</style><p style='text-align:center;'><img src='https://dev.interrogazioniprogrammate.tk/img/logo.svg' alt='" . _("Interrogazioni programmate") . "'
                                                           align='center' width='128' height='128'
                                                           onerror='this.src=\"https://dev.interrogazioniprogrammate.tk/img/logo.png\"'></p>
                        <h3 align='center' class='logo-text'>" . _("Interrogazioni Programmate") . "</h3>
            " . _("<p>Grazie per esserti registrato al portale Interrogazioni Programmate.</p>
			<p>Per attivare il tuo account clicca su questo link:") . " <a href='" . DIR . "activate.php?userID=$id&token=$activasion'>" . DIR . "activate.php?x=$id&y=$activasion</a></p>
			<p>" . _("Saluti, \n Il team di Interrogazioni Programmate") . "</p>";

            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();

            // Reindirizza alla pagina principale
            header('Location: register.php?action=joined');
            exit;

            // Altrimenti metti l'errore in una variabile (verrà mostrato successivamente)
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
    }

}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script defer src="https://dl.dropboxusercontent.com/s/94ajynkqcf3xg28/fa-all.min.js?dl=0"></script>

    <?php
    require("layout/header/favicon.php")
    ?>

    <title><?php echo _("Registrazione") . " - " . _("Interrogazioni Programmate") ?></title>
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <style type="text/css">
        html,
        body {
            height: 100%;
        }

        html {
            display: table;
            margin: auto;
        }

        body {
            display: table-cell;
            vertical-align: middle;
        }

        .margin {
            margin: 0 !important;
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
    <?php
    require("layout/header/background-change.php")
    ?>
    <!-- Import SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
</head>
<body>
<div class="container">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <?php
            // Controlla errori
            if (isset($error)) {
                foreach ($error as $er) {
                    echo '<script>swal({
  title: "' . _('Errore!') . '",
  html: "' . _('È stato riscontrato un errore durante la registrazione:<br>') . $er . '",
  type: "error",
});</script>';
                }
            }

            // se l'azione è "joined", allora mostra avviso di registrazione completata
            elseif (isset($_GET['action']) && $_GET['action'] == 'joined') {
                echo '<script>swal({
  title: "' . _('Sei registrato!') . '",
  text: "' . _('Registrazione completata, perfavore controlla la tua email (anche la cartella SPAM o di posta indesiderata) per attivare il tuo account.') . '",
  type: "success",
});</script>';
            }
            ?>
            <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off"
                  class="login-form">
                <div class="row">
                    <div class="input-field col s12 center">
                        <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                           align="center" width="128" height="128"
                                                           onerror="this.src='img/logo.png'"></p>
                        <h3 align="center" class="logo-text"><?php echo _("Interrogazioni Programmate") ?></h3>
                        <h4 align="center"><i class="material-icons">forward</i> <?php echo _("Registrazione") ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">person_outline</i>
                        <input id="username" name="username" type="text" class="validate" required minlength="4"
                               value="<?php
                        if (isset($_POST['username'])) {
                            echo $_POST['username'];
                        } ?>">
                        <label for="username" class="center-align"><?php echo _("Nome utente") ?></label>
                        <span class='helper-text'
                              data-error='<?php echo _("Nome utente troppo corto (almeno 4 caratteri)") ?>'
                              data-success='✓'></span>
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">mail_outline</i>
                        <input id="email" name="email" type="email" class="validate" required value="<?php
                        if (isset($_POST['email'])) {
                            echo $_POST['email'];
                        } ?>">
                        <label for="email" class="center-align"><?php echo _("Email") ?></label>
                        <span class='helper-text' data-error='<?php echo("Email non valida") ?>'
                              data-success="✓"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="password" name="password" type="password" class="validate" required minlength="8">
                        <label for="password"><?php echo _("Password") ?></label>
                        <span class='helper-text'
                              data-error='<?php echo _("Password troppo corta (almeno 8 caratteri)") ?>'
                              data-success='✓'></span>
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="confirm-password" name="confirm-password" type="password" required minlength="8">
                        <label for="confirm-password"><?php echo _("Ripeti password") ?></label>
                        <span class='helper-text' data-error='<?php echo _("Le password non corrispondono") ?>'
                              data-success='✓'></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light col s12" type="submit" name="submit" id="submit"
                                disabled>
                            <i class="fal fa-plus-circle"></i> <?php echo _("Registrati!") ?>
                        </button>
                    </div>
                    <div class="input-field col s12">
                        <p class="margin center medium-small sign-up"><?php echo _("Hai già un account?") ?> <a
                                    href="index.php" class="hover-underline-animation"><?php echo _("Accedi") ?></a></p>
                    </div>
                </div>
                <script>
                    $("#password").on("focusout", function (e) {
                        if ($(this).val() !== $("#confirm-password").val() || $(this).val() === '') {
                            $("#confirm-password").removeClass("valid").addClass("invalid");
                            $('#submit').prop('disabled', true);
                        } else {
                            $("#confirm-password").removeClass("invalid").addClass("valid");
                            $('#submit').prop('disabled', false);
                        }
                    });

                    $("#confirm-password").on("keyup", function (e) {
                        if ($("#password").val() !== $(this).val() || $(this).val() === '') {
                            $(this).removeClass("valid").addClass("invalid");
                            $('#submit').prop('disabled', true);
                        } else {
                            $(this).removeClass("invalid").addClass("valid");
                            $('#submit').prop('disabled', false);
                        }
                    });
                </script>
            </form>
            <div style="text-align: center;">
                Copyright © 2018 <?php echo _("Interrogazioni Programmate") ?>
                <p style="font-size:75%;"><?php echo _('L\'icona creata da <a href="http://www.freepik.com" title="Freepik">Freepik</a>
                    di
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> ha licenza <a
                            href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0"
                            target="_blank">Creative Commons BY 3.0</a>') ?></p>
            </div>
        </div>
    </div>
</div>
<!-- Compiled and minified JavaScript -->
<script src="js/materialize.min.js"></script>
</body>
</html>