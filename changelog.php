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
                "text" => __("Nuova UI: ora viene offerto uno stile grafico più simile a quello utilizzato da Google, grazie a [MDC](https://material.io/develop/web)"),
            ],
            [
                "type" => "new",
                "badge_text" => __("Nuovo"),
                "text" => __("Integrazione con Maicol07 Account (SSO): puoi ora utilizzare il tuo account di Maicol07 Network per accedere"),
            ],
            [
                "type" => "improved",
                "badge_text" => __("Migliorato"),
                "text" => __("Struttura interna dell'app"),
            ],
            [
                "type" => "deprecated",
                "badge_text" => __("Deprecato"),
                // NOTE FOR TRANSLATORS ABOUT THE [AAA](BBB) part: Don't translate the URL but only the AAA text (inside the square brackets)
                "text" => __("Gestione del profilo (quest'ultimo viene ora gestito da [Maicol07 Account](https://account.maicol07.it))"),
            ]
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
    <link href="<?php echo Utils::buildAssetsURI("app/assets/icons/mdi-outline/mdi-outline.min.css") ?>"
          rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/apple-touch-icon.png") ?>">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-32x32.png"); ?>">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-16x16.png"); ?>">
    <link rel="manifest" href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/site.webmanifest"); ?>">
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
              <p>' . $parser->text($feature->text) . '</p>
              <br>
           </div>';
        }
        echo '</div>
       </div>';
    }
    ?>
</section>
</body>
</html>