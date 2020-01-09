<?php
/**
 * Copyright (c) 2019.  Maicol07 - Tutti i diritti riservati - All rights reserved
 * Original template by Kodular (kodular.io)
 */

use src\Utils;

require_once 'core.php';
require_once DOCROOT . '/vendor/erusev/parsedown/Parsedown.php';

$changelog = [
    "1.0 - Apatite" => [
        "date" => "2020-XX-XX",
        "features" => [
            [
                "type" => "new",
                "badge_text" => __("Nuovo"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Nuova UI: ora viene offerto uno stile grafico più simile a quello utilizzato da Google, grazie a [MDC](https://material.io/develop/web), chiamato Material Design Refresh o Material Design 2"),
            ],
            [
                "type" => "new",
                "badge_text" => __("Nuovo"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Integrazione con [Maicol07 Account](https://account.maicol07.it) (SSO): puoi ora utilizzare il tuo account di Maicol07 Network per accedere (il tuo vecchio account su Interrogazioni Programmate è stato migrato automaticamente.
                Per motivi di privacy è necessario reimpostare la password al primo accesso)"),
            ],
            [
                "type" => "changed",
                "badge_text" => __("Cambiato"),
                "text" => __("Metodo di iscrizione degli utenti alle classi: ora gli utenti si iscrivono con un codice classe direttamente dalla dashboard.
                Con lo stesso codice è anche possibile visualizzare la classe e le relative liste (*in sola lettura*) come visitatori, senza effettuare l'accesso"),
            ],
            [
                "type" => "improved",
                "badge_text" => __("Migliorato"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Supporto a [PWA (Progressive Web Apps)](https://developers.google.com/web/progressive-web-apps): è possibile aggiungere alla propria schermata home una icona per accedere più velocemente all'app senza ogni volta dover aprire il browser e digitare l'URL 
                (**Funzione disponibile solo per browser supportati: controlla [QUI](https://www.goodbarber.com/blog/progressive-web-apps-browser-support-compatibility-a883/) per una lista aggiornata!**"),
            ],
            [
                "type" => "improved",
                "badge_text" => __("Migliorato"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Supporto alle lingue grazie alla libreria [Gettext](https://github.com/oscarotero/Gettext) di [Oscar Otero](https://github.com/oscarotero)"),
            ],
            [
                "type" => "improved",
                "badge_text" => __("Migliorato"),
                "text" => __("Struttura interna dell'app: sono state implementate le seguenti caratteristiche:
                - Classi;
                - Struttura modulare;
                - Composer per le librerie esterne;
                - Yarn/NPM per gli assets"),
            ],
            [
                "type" => "removed",
                "badge_text" => __("Rimosso"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Gestione del profilo (il proprio account può essere ora gestito da [Maicol07 Account](https://account.maicol07.it))"),
            ],
            [
                "type" => "removed",
                "badge_text" => __("Rimosso"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Impostazioni (le impostazioni riguardanti il proprio account possono essere ora gestite da [Maicol07 Account](https://account.maicol07.it))"),
            ],
        ]
    ],
    "0.1 - Alpha" => [
        "date" => "2018-09-21",
        "features" => [
            [
                "type" => "new",
                "badge_text" => __("Nuovo"),
                "text" => __("Rilascio iniziale")
            ]
        ]
    ]
];
$parser = new Parsedown();
?>
<!DOCTYPE html>
<!-- Copyright 2017 maicol07. -->
<html lang="<?php echo $lang ?>">
<head>
    <title><?php echo __("Note di rilascio") . ' - ' . __("Maicol07 Account") ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo Utils::buildAssetsURI("app/assets/css/style.css") ?>">
    <link rel="stylesheet" href="<?php echo Utils::buildAssetsURI("app/assets/css/changelog.css") ?>">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Manjari:400,700&display=swap"
          rel="stylesheet">
    <link href="<?php echo Utils::buildAssetsURI("app/assets/css/mdi-outline/mdi-outline.min.css") ?>"
          rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/apple-touch-icon.png") ?>">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-32x32.png"); ?>">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-16x16.png"); ?>">
    <link rel="manifest" href="<?php echo Utils::buildAssetsURI("/app/manifest.php"); ?>">
    <link rel="mask-icon" href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/safari-pinned-tab.svg"); ?>"
          color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon.ico"); ?>">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="msapplication-TileImage"
          content="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/mstile-144x144.png") ?>">
    <meta name="msapplication-config"
          content="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/browserconfig.xml"); ?>">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<section id="cd-timeline" class="cd-container">
    <?php
    foreach ($changelog as $title => $details) {
        $details = (object)$details;
        echo '
        <div class="cd-timeline-block">
        <div class="cd-timeline-img cd-picture"></div>
        <div class="cd-timeline-content">
           <span class="cd-date"><i class="mdi-outline-calendar_today"></i> ' . Utils::getLocaleDate($details->date, $lang) . '</span>
           <h2>' . $title . '</h2>';
        foreach ($details->features as $feature) {
            $feature = (object)$feature;
            echo '<div class="feature">
              <p class ="label ' . $feature->type . '">' . strtoupper($feature->badge_text) . '</p>
              ' . $parser->text($feature->text) . '
           </div>';
        }
        echo '</div>
       </div>';
    }
    ?>
</section>
</body>
</html>