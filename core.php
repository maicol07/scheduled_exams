<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
 *
 * Initial config (Constants, Errors, Database, and Authentication)
 *
 */
require_once __DIR__ . '/dir.php';
require_once DOCROOT . "/config/config.php";
/*
 *
 * Gettext Initialization
 *
 */
require DOCROOT . "/config/gettext.php";

//use Naucon\Breadcrumbs\Breadcrumbs;

$url = explode("/", $_SERVER['REQUEST_URI']);
$inside = in_array("app", $url);
if ($inside) {
    if (!$user->isAuthenticated()) {
        header("Location: " . BASEURL);
        exit;
    }
    /*$breadcrumbs = new Breadcrumbs();
    $breadcrumbs->add(__("Dashboard"), ROOTDIR . "/a/index.php");*/
} else {
    // Exceptions
    $exceptions = [
        "changelog", // Changelog viewer
        "src" // Src folder
    ];
    foreach ($exceptions as $exception) {
        $ex = in_array($exception, $url);
        if ($ex) {
            break;
        }
    }
    if ($logged and !$ex) {
        $url = BASEURL . "/app";
        header("Location: " . $url);
        exit;
    }
}