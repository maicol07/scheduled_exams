<?php

require('../includes/config.php');

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
    require("../layout/header/favicon.php")
    ?>
    <title>Dashboard - Interrogazioni Programmate</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="../css/materialize.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Import SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/init.js"></script>
</head>
<body>
<nav>
    <div class="nav-wrapper">
        <a data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <a href="index.php" class="brand-logo navimg" style="font-family: Raleway, sans-serif;"><img
                    src="img/logo_full_white.svg" alt="Interrogazioni programmate"
                    width="512" height="64"
                    onerror="this.src='img/logo_full_white.png'"></a>
        <ul class="right hide-on-med-and-down">
            <li class="active"><a href="#dashboard" class="waves-effect waves-light">Dashboard</a></li>
            <li><a href="#classi" class="waves-effect waves-light">Classi</a></li>
            <!-- Profile Dropdown Trigger -->
            <li><a href="#" class="dropdown-trigger navimg" data-target="dropdownprofile"><img
                            src="img/ui/user/male/user.svg" class="circle" width="50" height="50"></a></li>
        </ul>
    </div>
</nav>
<!-- Profile Dropdown Structure -->
<ul id="dropdownprofile" class="dropdown-content">
    <li><a href="#" class="waves-effect waves-light"><i class="fal fa-user"></i> Profilo</a></li>
    <li><a href="#" class="waves-effect waves-light"><i class="fal fa-cog"></i> Impostazioni</a></li>
    <li class="divider"></li>
    <li><a href="index.php?action=logout" class="waves-effect waves-light"><i class="fal fa-sign-out-alt"></i>
            Disconnettiti</a></li>
</ul>
<ul id="nav-mobile" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background">
                <?php
                $alba = 6;
                $giorno = 18;
                $ora = date("H");
                ?>
                <img src="
                <?php
                if ($ora >= 3 && $ora <= $alba) {
                    // Se l'ora attuale è maggiore di 3 e minore di $alba che è 6
                    echo "https://dl.dropboxusercontent.com/s/6m36z4d8zyi8ily/3%20-%20vVsVx5p.png?dl=0";
                } elseif ($ora > $alba && $ora <= $giorno) {
                    echo "https://dl.dropboxusercontent.com/s/ka8x2wcs46y03kd/4%20-%20ZFabsbM.png?dl=0";
                } else {
                    // Se nessuna delle precedenti condizioni è soddisfatta allora è notte
                    echo "https://dl.dropboxusercontent.com/s/0qe4q8rl7pfspal/2%20-%20lGi5EO6.png?dl=0";
                } ?>" style="object-fit: cover;">
            </div>
            <a href="#"><?php if ($userinfo["img"] == "") {
                    echo '<img class="circle" src="img/ui/user/male/user.svg">';
                } ?></a>
            <a href="#"><span class="white-text name"><?php
                    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
                        echo $userinfo["nome"] . " " . $userinfo["cognome"];
                    } else {
                        echo $_SESSION["username"];
                    } ?></span></a>
            <a href="#"><span class="white-text email"><?php echo $userinfo["email"]; ?></span></a>
        </div>
    </li>
    <li><a href="#" class="waves-effect">Classi</a></li>
    <li>
        <div class="divider"></div>
    </li>
    <li><a href="#" class="waves-effect"><i class="fal fa-user"></i> Profilo</a></li>
    <li><a href="#" class="waves-effect"><i class="fal fa-cog"></i> Impostazioni</a></li>
    <li>
        <div class="divider"></div>
    </li>
    <li><a href="index.php?action=logout" class="waves-effect"><i class="fal fa-sign-out-alt"></i> Disconnettiti</a>
    </li>
</ul>
<!-- START Body -->
<p align="right" style="padding-right: 20px" class="hide-on-small-only">Benvenuto <?php
    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
        echo $userinfo["nome"] . " " . $userinfo["cognome"];
    } else {
        echo $_SESSION["username"];
    } ?><br>
    <?php echo date('d/m/Y - H:i:s'); ?></p>
<div class="container">
    <section id="dashboard">
        <h2>Dashboard</h2>
        <p>Pagina non ancora disponibile!</p>
    </section>
    <section id="classi">
        <div class="row">
            <div class="col s6 m2 l3">
                <a href="#">
                    <div class="card">
                        <div class="card-action">
                            <span style="text-transform: uppercase;" class="center-block"><i
                                        class="fal fa-plus-circle"></i> Aggiungi classe</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php

            ?>
        </div>
    </section>
</div>
<!-- END Body -->
<!-- Compiled and minified JavaScript -->
<script src="../js/materialize.min.js"></script>
</body>
</html>