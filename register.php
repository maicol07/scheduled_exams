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
            <form class="login-form">
                <div class="row">
                    <div class="input-field col s12 center">
                        <p style="text-align:center;"><img src="img/logo.svg" alt="Interrogazioni programmate"
                                                           align="center" width="128" height="128"
                                                           onerror="this.src='img/logo.png'"></p>
                        <h3 align="center" style="font-variant: small-caps;">Interrogazioni Programmate</h3>
                        <h4 align="center"><i class="material-icons">forward</i> Registrazione</h4>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">person_outline</i>
                        <input id="username" type="text" class="validate">
                        <label for="username" class="center-align">Nome utente</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mail_outline</i>
                        <input id="email" type="email" class="validate">
                        <label for="email" class="center-align">Email</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="password" type="password" class="validate">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="password-again" type="password">
                        <label for="password-again">Ripeti password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <a href="register.php" class="btn waves-effect waves-light col s12"><i
                                    class="far fa-plus-circle"></i> Registrati ora!</a>
                    </div>
                    <div class="input-field col s12">
                        <p class="margin center medium-small sign-up">Hai già un account? <a
                                    href="index.php" class="loginlink">Accedi</a></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>
</html>