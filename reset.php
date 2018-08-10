<?php require('includes/config.php');

//if logged in redirect to app page
if ($user->is_logged_in()) {
    header('Location: app');
}

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM users WHERE resetToken = :token');
$stmt->execute(array(':token' => $_GET['key']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//if no token from db then kill the page
if (empty($row['resetToken'])) {
    $stop = 'Token inserito non valido, usa il link all\'interno dell\'email.';
    die;
} elseif ($row['resetComplete'] == 'Yes') {
    $stop = 'La tua password è già stata cambiata!';
    die;
}

//if form has been submitted process it
if (isset($_POST['submit'])) {

    //basic validation
    if (strlen($_POST['password']) < 3) {
        $error[] = 'La password inserita è troppo corta.';
    }

    if (strlen($_POST['confirm-password']) < 3) {
        $error[] = 'La conferma della password è troppo corta.';
    }

    if ($_POST['password'] != $_POST['confirm-password']) {
        $error[] = 'Le password inserite non corrispondono.';
    }

    //if no errors have been created carry on
    if (!isset($error)) {

        //hash the password
        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

        try {

            $stmt = $db->prepare("UPDATE users SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
            $stmt->execute(array(
                ':hashedpassword' => $hashedpassword,
                ':token' => $row['resetToken']
            ));

            //redirect to index page
            header('Location: index.php?action=resetAccount');
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
    <title>Reset Password - Interrogazioni Programmate</title>
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
        }
    </style>
    <?php
    require("layout/header/background-change.php")
    ?>
    <link rel="stylesheet" href="css/style.css">
    <!-- Import SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php
if (isset($error)) {
    foreach ($error as $er) {
        echo '<script>swal({
  title: "Errore!",
  text: "È stato riscontrato un errore durante il reset della password:\n' . $er . '",
  icon: "error"
});</script>';
    }
}

if (isset($stop)) {
    echo '<script>swal({
  title: "Attenzione!",
  text: ' . $stop . ',
  icon: "warning"
});</script>';
}
?>
<div class="container">
    <div class="row">
        <div id="login-page" class="row">
            <div class="col s12 z-depth-6 card-panel">
                <form role="form" method="post" action="" autocomplete="off" class="login-form">
                    <div class="row">
                        <div class="input-field col s12 center">
                            <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                               align="center" width="128" height="128"
                                                               onerror="this.src='img/logo.png'"></p>
                            <h3 align="center" class="logo-text">Interrogazioni Programmate</h3>
                            <h4 align="center"><i class="material-icons">forward</i> Resetta Password</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">lock_outline</i>
                            <input id="password" name="password" type="password" class="validate" required
                                   minlength="8">
                            <label for="password">Password</label>
                            <span class='helper-text' data-error='Password troppo corta (almeno 8 caratteri)'
                                  data-success='✓'></span>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">lock_outline</i>
                            <input id="confirm-password" name="confirm-password" type="password" required minlength="8">
                            <label for="confirm-password">Ripeti password</label>
                            <span class='helper-text' data-error='Le password non corrispondono'
                                  data-success='✓'></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn waves-effect waves-light col s12" type="submit" name="submit"
                                    id="submit">
                                <i class="material-icons left">refresh</i> Cambia password!
                            </button>
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
                    Copyright © 2018 Interrogazioni Programmate
                    <p style="font-size:75%;">L'icona creata da <a href="http://www.freepik.com"
                                                                   title="Freepik">Freepik</a>
                        di
                        <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> ha licenza <a
                                href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0"
                                target="_blank">Creative Commons BY 3.0</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/materialize.min.js"></script>
</body>
</html>