<?php
//include config
require_once('includes/config.php');

language("login");

//check if already logged in move to home page
if ($user->is_logged_in()) {
    if (isset($_GET["redir"]) and !$_GET["redir"] == "") {
        header('Location: app/' . $_GET["redir"]);
    } else {
        header("Location: app");
    }
}

//process login form if submitted
if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        $_SESSION['username'] = $username;
        if (isset($_GET["redir"]) and !$_GET["redir"] == "") {
            header('Location: app/' . $_GET["redir"]);
        } else {
            header("Location: app");
        }
        exit;

    } else {
        $error[] = _('Il nome utente e la password inseriti non corrispondono oppure il tuo account non è ancora attivo.');
    }

}//end if submit
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
    <title><?php echo _("Accesso") . " - " . _("Interrogazioni Programmate") ?></title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

        .margin {
            margin: 0 !important;
        }
    </style>
    <?php
    require("layout/header/background-change.php")
    ?>
    <link rel="stylesheet" href="css/style.css">
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
  html: "' . _("È stato riscontrato un errore durante l'accesso:<br>") . $er . '",
  type: "error"
})</script>';
    }
}
if (isset($_GET['action'])) {
    //check the action
    switch ($_GET['action']) {
        case 'active':
            $successtitle = _("Sei stato attivato!");
            $successmsg = _("Il tuo account è ora attivo e puoi accedere.");
            break;
        case 'reset':
            $successtitle = _("Azione eseguita con successo!");
            $successmsg = _("Perfavore controlla la tua posta in arrivo (anche SPAM o posta indesiderata) per il link di reset della password.");
            break;
        case 'resetAccount':
            $successtitle = _("Password cambiata!");
            $successmsg = _("Hai cambiato la password, puoi ora accedere.");
            break;
    }
}
if (isset($successmsg)) {
    echo '<script>swal({
  title: "' . $successtitle . '",
  text: "' . $successmsg . '",
  type: "success"
});</script>';
}
?>
<div class="container">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <form role="form" method="post" action="<?php
            if (isset($_GET["redir"]) and $_GET["redir"] !== "") {
                echo "?redir=" . $_GET["redir"];
            }
            ?>" autocomplete="off" class="login-form">
                <div class="row">
                    <div class="input-field col s12 center">
                        <p style="text-align:center;"><!--suppress JSUnusedGlobalSymbols -->
                            <img src="img/logo.svg" alt="<?php echo _("Interrogazioni programmate"); ?>"
                                 align="center" width="128" height="128"
                                 onerror="this.src='img/logo.png'"></p>
                        <h3 align="center" class="logo-text"><?php echo _("Interrogazioni Programmate"); ?></h3>
                        <h4 align="center"><i class="material-icons">forward</i> <?php echo _("Accesso"); ?></h4>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">person_outline</i>
                        <input class="validate" id="username" name="username" type="text">
                        <label for="username" class="center-align"><?php echo _("Email o nome utente") ?></label>
                        <span class='helper-text' data-error='<?php echo _("Nome utente non valido") ?>'
                              data-success="✓"></span>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="password" name="password" type="password">
                        <label for="password"><?php echo _("Password") ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label>
                            <input id="remember-me" type="checkbox"/>
                            <span><?php echo _("Ricordami") ?></span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light col s12" type="submit" name="submit" id="submit">
                            <i class="far fa-sign-in-alt"></i> <?php echo _("Accedi") ?>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <p class="margin medium-small hover-underline-animation blue-text text-darken-3"><a
                                    href="register.php"><?php echo _("Registrati ora!") ?></a></p>
                    </div>
                    <div class="input-field col s6 m6 l6" align="right">
                        <p class="margin right-align medium-small hover-underline-animation"><a
                                    href="forgot-password.php"><?php echo _("Password dimenticata?") ?></a></p>
                    </div>
                </div>
            </form>
            <div style="text-align: center;">
                Copyright © 2018 <?php echo _("Interrogazioni Programmate") ?>
                <p style="font-size:75%;"><?php echo _('L\'icona creata da <a href="http://www.freepik.com" title="Freepik">Freepik</a>
                    di
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> ha licenza <a
                            href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0"
                            target="_blank">Creative Commons BY 3.0</a>') ?></p>
                <script language="Javascript" type="text/javascript">
                    function changelog() {
                        window.open("versionpopup.php", "<?php echo _("Cronologia versioni") ?>", "width=500,height=500,left=125,top=125");
                    }
                </script>
                <a href="javascript:changelog();" align="center"
                   class="waves-effect btn-flat hover-underline-animation"><i
                            class="tiny material-icons left">new_releases</i>
                    Versione <?php
                    $myfile = fopen("includes/version.txt", "r") or die(_("Impossibile aprire il file!"));
                    echo fread($myfile, filesize("includes/version.txt"));
                    fclose($myfile);
                    ?> </a>
            </div>
            <br>
        </div>
    </div>
</div>
<!-- Compiled and minified JavaScript -->
<script src="js/materialize.min.js"></script>
</body>
</html>