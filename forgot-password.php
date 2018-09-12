<?php /** @noinspection ALL */
require('includes/config.php');

language("login");

//if logged in redirect to application page
if ($user->is_logged_in()) {
    header('Location: app');
}

//if form has been submitted process it
if (isset($_POST['submit'])) {

    //email validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = _('Indirizzo email non valido o non inserito!');
    } else {
        $stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute(array(':email' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row['email'])) {
            $error[] = _("L' email inserita non ha una corrispondenza nei nostri database. Sei sicuro di esserti registrato correttamente o di aver inserito l'email corretta?");
        }

    }

    //if no errors have been created carry on
    if (!isset($error)) {

        //create the activasion code
        $token = md5(uniqid(rand(), true));

        try {

            $stmt = $db->prepare("UPDATE users SET resetToken = :token, resetComplete='No' WHERE email = :email");
            $stmt->execute(array(
                ':email' => $row['email'],
                ':token' => $token
            ));

            //send email
            $to = $row['email'];
            $subject = _("Reset della password") . " - " . _("Interrograzioni Programmate");
            $body = "<style>
@import url('https://fonts.googleapis.com/css?family=Black+Ops+One');
.logo-text {
    font-family: 'Black Ops One', cursive !important;
    font-variant: small-caps !important;
    color: #003471;
}
</style><p style=\"text-align:center;\"><img src=\"https://dev.interrogazioniprogrammate.tk/img/logo.svg\" alt=\"Interrogazioni programmate\"
                                                           align=\"center\" width=\"128\" height=\"128\"
                                                           onerror=\"this.src='https://dev.interrogazioniprogrammate.tk/img/logo.png'\"></p>
                        <h3 align=\"center\" class='logo-text'>" . _("Interrogazioni Programmate") . "</h3>
                        " . _("<p>Qualcuno ha richiesto il reset della tua password.</p>
			<p>Se non sei stato tu , ignora questa email e non succederà niente.</p>
			<p>Per resettare la tua password, visita il seguente indirizzo:") . " <a href='" . DIR . "reset.php?key=$token'>" . _("Resetta la password") . "</a></p>
			<p>" . _("Se il collegamento sopra non dovesse funzionare, copia e incolla nel browser il seguente indirizzo:") . "</p>
			<p align='center'>" . DIR . "reset.php?key=$token</p>";

            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();

            //redirect to index page
            header('Location: index.php?action=reset');
            exit;

            //else catch the exception and show the error.
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

    <title><?php echo _("Password dimenticata") . " - " . _("Interrogazioni Programmate") ?></title>
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
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
            min-height: 100vh;
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
<?php
if (isset($error)) {
    foreach ($error as $er) {
        echo '<script>swal({
  title: "' . _("Errore!") . '",
  html: "' . _("È stato riscontrato un errore durante il recupero della password:") . '<br>' . $er . '",
  type: "error",
});</script>';
    }
}
?>
<div class="container">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <div class="row">
                <div class="input-field col s12 center">
                    <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                       align="center" width="128" height="128"
                                                       onerror="this.src='img/logo.png'"></p>
                    <h3 align="center" class="logo-text"><?php echo _("Interrogazioni Programmate") ?></h3>
                    <h4 align="center"><i class="material-icons">forward</i> <?php echo _("Recupera Password") ?></h4>
                </div>
            </div>
            <form class="col s12" method="post">
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mail_outline</i>
                        <input class="validate" id="email" name="email" type="email">
                        <label for="email"><?php echo _("Email") ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light col s12" type="submit" name="submit" id="submit">
                            <i class="far fa-arrow-alt-right"></i> <?php echo _("Recupera password!") ?>
                        </button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small"><a href="register.php"
                                                      class="hover-underline-animation"><?php echo _("Registrati!") ?></a>
                    </p>
                </div>
                <div class="input-field col s6 m6 l6">
                    <p class="margin right-align medium-small"><a href="index.php"
                                                                  class="hover-underline-animation"><?php echo _("Accedi ora!") ?></a>
                    </p>
                </div>
            </div>
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