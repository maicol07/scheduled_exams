<?php
//CSP only works in modern browsers Chrome 25+, Firefox 23+, Safari 7+
$cspheader = "Content-Security-Policy:" .
    "connect-src * 'unsafe-inline';" . // XMLHttpRequest (AJAX request), WebSocket or EventSource.
    "default-src * 'unsafe-inline' 'unsafe-eval';" . // Default policy for loading html elements
    "frame-ancestors *;" . //allow parent framing - this one blocks click jacking and ui redress
    "frame-src *;" . // vaid sources for frames
    "media-src *;" . // vaid sources for media (audio and video html tags src)
    "img-src * data: blob: 'unsafe-inline';" . // images
    "object-src *; " . // valid object embed and applet tags src
    "report-uri " . BASEURL . "/config/csp_violation_report.php;" . //A URL that will get raw json data in post that lets you know what was violated and blocked
    "script-src * 'unsafe-inline' 'unsafe-eval' ;" . // allows js from self, jquery and google analytics.  Inline allows inline js
    "style-src * 'unsafe-inline'";// allows css from self and inline allows inline css

/*
 * Sends the Header in the HTTP response to instruct the Browser how it should handle content and what is whitelisted
 * Its up to the browser to follow the policy which each browser has varying support
 */
header($cspheader);
/*
 * X-Frame-Options is not a standard (note the X- which stands for extension not a standard)
 * This was never officially created but is supported by a lot of the current browsers in use in 2015 and will block iframing of your website
 */
header('X-Frame-Options: SAMEORIGIN');

use src\Utils;

?>
<!DOCTYPE html>
<html class="loading" lang="<?php echo $lang ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, minimal-ui">
    <meta name="description" content="Scheduled Exams - Manage your exams online with your class">
    <meta name="keywords" content="maicol07, scheduled, exams, interrogazioni, programmate">
    <meta name="author" content="maicol07">

    <title><?php echo sprintf("%s - " . __("Interrogazioni Programmate"), $title) ?></title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:400,700&display=swap"
          rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/apple-touch-icon.png") ?>">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/favicon-32x32.png"); ?>">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/favicon-16x16.png"); ?>">
    <link rel="manifest" href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/site.webmanifest"); ?>">
    <link rel="mask-icon" href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/safari-pinned-tab.svg"); ?>"
          color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo Utils::buildAssetsURI("/assets/img/favicon/favicon.ico"); ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config"
          content="<?php echo Utils::buildAssetsURI("/assets/img/favicon/browserconfig.xml"); ?>">
    <meta name="theme-color" content="#ffffff">

    <?php
    $styles = [
        // MDC for web
        "vendor/web-assets/material-components-web/dist/material-components-web.min.css",
        // Flag icon CSS
        "vendor/web-assets/flag-icon-css/css/flag-icon.min.css",
        // Cookie consent
        "vendor/web-assets/cookieconsent/build/cookieconsent.min.css",
        // Styles
        "app/assets/css/style.min.css",
        // Material Design Outline Icons
        "app/assets/icons/mdi-outline/mdi-outline.min.css",
    ];
    if (!empty($include_styles)) {
        $styles = array_merge($styles, $include_styles);
    }
    echo Utils::buildAssetsImport($styles);
    ?>
</head>
<?php
if (isset($body) and !$body) {
    return;
}
?>
<body>
<!-- Preloader
<script src="<?php echo Utils::buildAssetsURI("a/assets/js/custom/preloader.min.js") ?>"></script>-->
<?php
if (!isset($navbar) or $navbar) {
    require_once DOCROOT . "/app/layout/navbar.php";
}
if (!isset($left_sidebar) or $left_sidebar) {
    require_once DOCROOT . "/app/layout/left_sidebar.php";
}
?>
<div class="mdc-drawer-app-content">
    <?php


    ?>
    <!-- BEGIN: Page Main-->
    <main id="main-content">
        <div class="mdc-top-app-bar--fixed-adjust">
            <?php
            /*if (!isset($bc) or $bc == true) {
                $breadcrumbs->add($title);
                echo '
            <!-- START BREADCRUMBS -->
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0">' . $title . '</h5>
                            <ol class="breadcrumbs mb-0">';
                foreach($breadcrumbs as $breadcrumb){
                    if ($breadcrumb->hasUrl()) {
                        $internal = '"><a href="' . $breadcrumb->getUrl() . '">';
                    } else {
                        $internal = ' active">';
                    }
                    echo '<li class="breadcrumb-item' . $internal . $breadcrumb->getTitle() . '</a></li>';
                }
                echo '
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
             <!-- END BREADCRUMBS -->';
            }*/
            ?>
