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

$detector = new Mobile_Detect();

$url = explode("/", $_SERVER['REQUEST_URI']);
$inside = in_array("app", $url);
if ($inside) {
    if (empty($noauth) and !$user->isAuthenticated()) {
        header("Location: " . BASEURL);
        exit;
    }
} else {
    // Exceptions
    $exceptions = [
        "changelog", // Changelog viewer
        "src", // Src folder
        "config" // CSP violation report
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

/*
 * Assets
 */

use Stolz\Assets\Manager;

require_once DOCROOT . '/config/assets.php';


// Set config options
$config = [
    'pipeline' => false, // Bugged
    'pipeline_dir' => 'min',
    'public_dir' => ROOTDIR,
    'css_dir' => ROOTDIR . "/app/assets/css",
    'js_dir' => ROOTDIR . "/app/assets/js",
    'packages_dir' => "/vendor/web-assets",
    'npm_dir' => 'vendor/web-assets',
    'docroot' => DOCROOT,
    'autoload' => array_merge($css, $js) // Autoload all the default assets
];

// Instantiate the library
$assets = new Manager($config);