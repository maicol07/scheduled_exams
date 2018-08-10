<?php

require_once('../includes/config.php');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: ../index.php');
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
    <script defer src="https://dl.dropboxusercontent.com/s/94ajynkqcf3xg28/fa-all.min.js?dl=0"></script>
    <?php
    require("layout/favicon.php")
    ?>
    <title><?php echo $title; ?> - Interrogazioni Programmate</title>
    <!--Import Google Icon Font
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="../css/materialize.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Import SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--suppress JSUnusedLocalSymbols -->
    <script>
        var userID = <?php echo $_SESSION["userID"]; ?>;
        var username = "<?php echo $_SESSION["username"]; ?>";
    </script>
    <script src="js/init.js"></script>
    <script src="js/<?php echo $inc_script; ?>.js"></script>
</head>