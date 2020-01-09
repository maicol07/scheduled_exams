<?php

use Debugbar\StandardDebugBar;

if (defined("DOCROOT")) {
    require_once DOCROOT . "/config/class_loader.php";
} else {
    require_once "class_loader.php";
}

/**
 * Whoops (only dev)
 */
if (!PRODUCTION) {
    ini_set('display_errors', true);
    // Whoops initialization
    $whoops = new Whoops\Run();
    if (Whoops\Util\Misc::isAjaxRequest()) {
        // Enable errors management for AJAX requests
        $whoops->prependHandler(new Whoops\Handler\JsonResponseHandler());
    } else {
        $whoops->prependHandler(new Whoops\Handler\PrettyPageHandler);
    }
    $whoops->register();

    /*
     * PHP Debug Bar
     */
    require_once DOCROOT . "/vendor/maximebf/debugbar/src/DebugBar/StandardDebugBar.php";
    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();
    $debugbarRenderer->setBaseUrl(ROOTDIR . '/vendor/maximebf/debugbar/src/DebugBar/Resources');
}

/**
 * Sentry
 */
Sentry\init([
    'dsn' => 'https://d7fe1b1c89eb4dd58faf6a213052235b@sentry.io/1810091',
    'release' => file_get_contents(DOCROOT . "/VERSION"),
    'environment' => PRODUCTION ? "prod" : "dev"
]);