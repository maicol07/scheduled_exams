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

use App\Utils;

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

    <title><?php echo __("%s - Interrogazioni Programmate", $title) ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
            href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
            rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/apple-touch-icon.png") ?>">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-32x32.png"); ?>">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon-16x16.png"); ?>">
    <link rel="manifest" href="<?php echo Utils::buildAssetsURI("/app/manifest"); ?>">
    <link rel="mask-icon" href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/safari-pinned-tab.svg"); ?>"
          color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/favicon.ico"); ?>">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="msapplication-TileImage"
          content="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/mstile-144x144.png") ?>">
    <meta name="msapplication-config"
          content="<?php echo Utils::buildAssetsURI("/app/assets/img/favicon/browserconfig.xml"); ?>">
    <meta name="theme-color" content="#ffffff">

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?php echo ROOTDIR ?>/app/sw.js').then((registration) => {
                console.log('Registration successful, scope is:', registration.scope);
            }).catch((error) => {
                console.log('Service worker registration failed, error:', error);
            });
        }
    </script>

    <?php
    // CSS
    echo $assets->css()
    ?>
</head>
<?php
if (isset($body) and !$body) {
    return;
}
if (!empty($debugbar) and isset($debugbarRenderer)) {
    echo $debugbarRenderer->renderHead();
}
?>
<body class="mdc-typography">
<!-- Preloader -->
<script src="<?php echo Utils::buildAssetsURI("app/assets/js/preloader.min.js") ?>"></script>
<?php
if (!isset($navbar) or $navbar) {
    require_once DOCROOT . "/app/layout/navbar.php";
}
if (!isset($left_sidebar) or $left_sidebar) {
    require_once DOCROOT . "/app/layout/left_sidebar.php";
}
?>
<div class="mdc-drawer-app-content">
    <!-- BEGIN: Page Main-->
    <main id="main-content">
        <div class="mdc-top-app-bar--fixed-adjust">
