<?php /** @noinspection ALL */
require('includes/config.php');

//if logged in redirect to members page
if ($user->is_logged_in()) {
    header('Location: app');
}

//if form has been submitted process it
if (isset($_POST['submit'])) {

    //email validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Indirizzo email corretto!';
    } else {
        $stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
        $stmt->execute(array(':email' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row['email'])) {
            $error[] = 'L\' email inserita non ha una corrispondenza nei nostri database. Sei sicuro di esserti registrato correttamente o di aver inserito l\'email corretta?';
        }

    }

    //if no errors have been created carry on
    if (!isset($error)) {

        //create the activasion code
        $token = md5(uniqid(rand(), true));

        try {

            $stmt = $db->prepare("UPDATE members SET resetToken = :token, resetComplete='No' WHERE email = :email");
            $stmt->execute(array(
                ':email' => $row['email'],
                ':token' => $token
            ));

            //send email
            $to = $row['email'];
            $subject = "Reset della password - Interrograzioni Programmate";
            $body = "<p>Qualcuno ha richiesto il reset della tua password.</p>
			<p>Se non sei stato tu , ignora questa email e non succederà niente.</p>
			<p>Per resettare la tua password, visita il seguente indirizzo: <a href='" . DIR . "resetPassword.php?key=$token'>Resetta la password</a></p>
			<p>Se il collegamento sopra non dovesse funzionare, copia e incolla nel browser il seguente indirizzo:</p>
			<p align='center'>" . DIR . "resetPassword.php?key=$token</p>";

            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();

            //redirect to index page
            header('Location: login.php?action=reset');
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

    <title>Password dimenticata - Interrogazioni Programmate</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
    <style type="text/css">
        html,
        body {
            height: 100%;
            font-family: 'Roboto', sans-serif;
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php
if (isset($error)) {
    foreach ($error as $er) {
        echo '<script>swal({
  title: "Errore!",
  text: "È stato riscontrato un errore durante al registrazione:\n' . $er . '",
  icon: "error",
});</script>';
    }
}
?>
<div class="container">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <form class="login-form">
                <div class="row">
                    <div class="input-field col s12 center">
                        <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                           align="center" width="128" height="128"
                                                           onerror="this.src='img/logo.png'"></p>
                        <h3 align="center" style="font-variant: small-caps;">Interrogazioni Programmate</h3>
                        <h4 align="center"><i class="material-icons">forward</i> Recupera Password</h4>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mail_outline</i>
                        <input class="validate" id="email" type="email">
                        <label for="email" data-error="wrong" data-success="right" class="center-align">Email</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <a href="forgot-password.php" class="btn waves-effect waves-light col s12"><i
                                    class="far fa-arrow-alt-right"></i> Recupera la mia password</a>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <p class="margin medium-small"><a href="register.php" class="hover-underline-animation">Registrati!</a>
                        </p>
                    </div>
                    <div class="input-field col s6 m6 l6">
                        <p class="margin right-align medium-small"><a href="index.php"
                                                                      class="hover-underline-animation">Accedi ora!</a>
                        </p>
                    </div>
                </div>
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
<script src="js/materialize.min.js"></script>
</body>
</html>