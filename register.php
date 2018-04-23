<?php
require('includes/config.php');
// se l'utente ha già eseguito l'accesso reindirizzalo alla sua pagina
if ($user->is_logged_in()) {
    header('Location: app/index.php');
}

// se il form è stato inviato, processalo
if (isset($_POST['submit'])) {

    // Validazione aggiuntiva (in caso di alcuni bug o trucchetti)
    if (strlen($_POST['username']) < 4) {
        $error[] = 'Il nome utente inserito è troppo corto!';
    } else {
        $stmt = $db->prepare('SELECT username FROM users WHERE username = :username');
        $stmt->execute(array(':username' => $_POST['username']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['username'])) {
            $error[] = '<span style="color: red; ">Il nome utente inserito è già in uso. Sceglierne un altro.</span>';
        }

    }

    if (strlen($_POST['password']) < 8) {
        $error[] = '<span style="color: red; ">La password inserita è troppo corta.</span>';
    }

    if (strlen($_POST['passwordConfirm']) < 8) {
        $error[] = '<span style="color: red; ">La conferma della password inserita è troppo corta.</span>';
    }

    if ($_POST['password'] != $_POST['passwordConfirm']) {
        $error[] = '<span style="color: red; ">Le password inserite non corrispondono.</span>';
    }

    // VALIDAZIONE EMAIL
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = '<span style="color: red; ">L\'email inserita non è valida. Perfavore inserirne una corretta.</span>';
    } else {
        $stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute(array(':email' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['email'])) {
            $error[] = '<script>alert("L\'email inserita è già utilizzata.")</script>';
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
            $stmt = $db->prepare('INSERT INTO users (username,password,email,active) VALUES (:username, :password, :email, :active)');
            $stmt->execute(array(
                ':username' => $_POST['username'],
                ':password' => $hashedpassword,
                ':email' => $_POST['email'],
                ':active' => $activasion
            ));
            $id = $db->lastInsertId('userID');

            //send email
            $to = $_POST['email'];
            $subject = "Conferma della registrazione - Interrogazioni Programmate";
            $body = "<p style=\"text-align:center;\"><img src=\"img/logo.svg\" alt=\"Interrogazioni programmate\"
                                                           align=\"center\" width=\"128\" height=\"128\"
                                                           onerror=\"this.src='img/logo.png'\"></p>
                        <h3 align=\"center\" style=\"font-variant: small-caps;\">Interrogazioni Programmate</h3>
            <p>Grazie per esserti registrato al portale Interrogazioni Programmate.</p>
			<p>Per attivare il tuo account clicca su questo link: <a href='" . DIR . "activate.php?x=$id&y=$activasion'>" . DIR . "activate.php?x=$id&y=$activasion</a></p>
			<p>Saluti, \n Il team di Interrogazioni Programmate</p>";

            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();

            // Reindirizza alla pagina principale
            header('Location: index.php?action=joined');
            exit;

            // Altrimenti mostra l'errore
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
            echo "<script>alert($error)</script>";
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

    <!-- START Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="msapplication-TileImage" content="img/favicon/mstile-144x144.png">
    <meta name="msapplication-config" content="img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- END Favicon -->

    <title>Registrazione - Interrogazioni Programmate</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
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

        .loginlink {
            position: relative;
            text-decoration: none;
        }

        .loginlink:before {
            content: "";
            position: absolute;
            width: 102%;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #039be5;
            visibility: hidden;
            -webkit-transform: scaleX(0);
            transform: scaleX(0);
            -webkit-transition: all 0.3s ease-in-out 0s;
            transition: all 0.3s ease-in-out 0s;
        }

        .loginlink:hover:before {
            visibility: visible;
            -webkit-transform: scaleX(1);
            transform: scaleX(1);
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
    <?php
    $alba = 6;
    $giorno = 18;
    $ora = date("H");
    ?>
    <?php if ($ora >= 3 && $ora <= $alba) { ?>
        <!-- Se l'ora attuale è maggiore di 3 e minore di $alba che è 6 -->
        <style>
            /* Mostra questo codice css per l'alba */
            body {
                background-image: url(https://dl.dropboxusercontent.com/s/mlsyprm4otlrz6m/1%20-%20nDix8XF.png?dl=0);
                background-position: center;
                background-size: auto;
                background-repeat: repeat;
            }
        </style>

    <?php } elseif ($ora > $alba && $ora <= $giorno) { ?>

        <!-- Se l'ora attuale è maggiore di 6 ($alba) e minore di $giorno che è 18 -->
        <style>
            /* Mostra questo codice css per il giorno */
            body {
                background-image: url(https://dl.dropboxusercontent.com/s/1k68ull9ih9j547/2%20-%20nmGS0kk.png?dl=0);
                background-position: center;
                background-size: auto;
                background-repeat: repeat;
            }
        </style>

    <?php } else { ?>

        <!-- Se nessuna delle precendenti condizioni è soddisfatta allora è notte -->

        <style>
            /* Mostra questo codice css per la notte */
            body {
                background-image: url(https://dl.dropboxusercontent.com/s/srfxau9184zsg7h/4%20-%20dNd6nJP.png?dl=0);
                background-position: center;
                background-size: auto;
                background-repeat: repeat;
            }
        </style>

    <?php } ?>
</head>
<body>
<div class="container">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off"
                  class="login-form">
                <div class="row">
                    <div class="input-field col s12 center">
                        <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                           align="center" width="128" height="128"
                                                           onerror="this.src='img/logo.png'"></p>
                        <h3 align="center" style="font-variant: small-caps;">Interrogazioni Programmate</h3>
                        <h4 align="center"><i class="material-icons">forward</i> Registrazione</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">person_outline</i>
                        <input id="username" type="text" class="validate" required minlength="4" value="<?php
                        if (isset($_POST['username'])) {
                            echo $_POST['username'];
                        } ?>">
                        <label for="username" class="center-align">Nome utente</label>
                        <span class='helper-text' data-error='Nome utente troppo corto (almeno 4 caratteri)'
                              data-success='✓'></span>
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">mail_outline</i>
                        <input id="email" type="email" class="validate" required value="<?php
                        if (isset($_POST['email'])) {
                            echo $_POST['email'];
                        } ?>">
                        <label for="email" class="center-align">Email</label>
                        <span class='helper-text' data-error='Email non valida' data-success="✓"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="password" type="password" class="validate" required minlength="8">
                        <label for="password">Password</label>
                        <span class='helper-text' data-error='Password non valida' data-success='✓'></span>
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="confirm-password" type="password" required minlength="8">
                        <label for="confirm-password">Ripeti password</label>
                        <span class='helper-text' data-error='Le password non corrispondono' data-success='✓'></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light col s12" type="submit" name="action" id="submit"
                                disabled>
                            <i class="fal fa-plus-circle"></i> Registrati!
                        </button>
                    </div>
                    <div class="input-field col s12">
                        <p class="margin center medium-small sign-up">Hai già un account? <a
                                    href="index.php" class="loginlink">Accedi</a></p>
                    </div>
                </div>
                <script>
                    $("#password").on("focusout", function (e) {
                        if ($(this).val() !== $("#confirm-password").val()) {
                            $("#confirm-password").removeClass("valid").addClass("invalid");
                            $('#submit').prop('disabled', true);
                        } else {
                            $("#confirm-password").removeClass("invalid").addClass("valid");
                            $('#submit').prop('disabled', false);
                        }
                    });

                    $("#confirm-password").on("keyup", function (e) {
                        if ($("#password").val() !== $(this).val()) {
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
                Copyright © 2017 Interrogazioni Programmate
                <p style="font-size:75%;">L'icona creata da <a href="http://www.freepik.com" title="Freepik">Freepik</a>
                    di
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> ha licenza <a
                            href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0"
                            target="_blank">Creative Commons BY 3.0</a></p>
            </div>
        </div>
    </div>
</div>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>
</html>