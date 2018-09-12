<?php /** @noinspection ALL */

require_once('../includes/config.php');

$locale = language("app");

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: ../index.php?redir=' . $filename . '');
}

if (isset($_GET["action"]) and $_GET["action"] == "logout") {

    //logout
    $user->logout();

    //logged in return to index page
    header('Location: index.php');
    exit;
}
$userinfo = $user->get_data();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    require("layout/favicon.php")
    ?>
    <title><?php echo _($title) . " - " . _("Interrogazioni Programmate") ?></title>
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="../css/materialize.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Import SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--suppress JSUnusedLocalSymbols -->
    <script>
        var userID = <?php echo $_SESSION["userID"]; ?>;
        var username = "<?php echo $_SESSION["username"]; ?>";
    </script>
    <?php
    require_once("js/init.php");
    require_once("js/" . $inc_script . ".php");
    ?>
</head>
<body>
<!-- Start Page Loading -->
<div id="preloader" class="lds-css ng-scope">
    <div style="width:100%;height:100%" class="lds-dual-ring">
        <div>

        </div>
    </div>
</div>
<!-- End Page Loading -->