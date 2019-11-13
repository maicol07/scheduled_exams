<?php
/**
 * Copyright (c) 2019.  Maicol07 - Tutti i diritti riservati - All rights reserved
 */

if (!defined('DOCROOT')) {
    // $rootdir individuation
    $rootdir = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')) . '/';
    if (strrpos($rootdir, '/' . basename(__DIR__) . '/') !== false) {
        $rootdir = substr($rootdir, 0, strrpos($rootdir, '/' . basename(__DIR__) . '/')) . '/' . basename(__DIR__);
    } else {
        $rootdir = '/';
    }
    $rootdir = rtrim($rootdir, '/');
    $rootdir = str_replace('%2F', '/', rawurlencode($rootdir));

    // $baseurl individuation
    require_once __DIR__ . "/src/Utils.php";
    $baseurl = (src\Utils::isHTTPS(true) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $rootdir;

    // Set global vars
    define('DOCROOT', __DIR__);
    define('ROOTDIR', $rootdir);
    define('BASEURL', $baseurl);
    if (!defined("PRODUCTION")) {
        if (BASEURL == "app.scheduledexams.tk") {
            define("PRODUCTION", true);
        } else {
            define("PRODUCTION", false);
        }
    }
}