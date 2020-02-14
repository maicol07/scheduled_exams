<?php

use Debugbar\StandardDebugBar;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

if (!defined("DOCROOT")) {
    define('DOCROOT', '');
}
require_once DOCROOT . "/config/class_loader.php";

/*
 * Monolog error logging
 */
// Create a log channel
$log = new Logger('Logs');
$handlers = [];
$handlers[] = new StreamHandler(DOCROOT . '/logs/error.log', Logger::ERROR);
// Log files rotation
$handlers[] = new RotatingFileHandler(DOCROOT . '/logs/error.log', 0, Monolog\Logger::ERROR);
// Format logs lines
$pattern = '[%datetime%] %channel%.%level_name%: %message% %context%' . PHP_EOL . '%extra% ' . PHP_EOL;
$monologFormatter = new Monolog\Formatter\LineFormatter($pattern);
$monologFormatter->includeStacktraces(!PRODUCTION);

foreach ($handlers as $handler) {
    $handler->setFormatter($monologFormatter);
    $log->pushHandler(new FilterHandler($handler, [$handler->getLevel()]));
}

/**
 * Whoops (only dev)
 */
$whoops = null;
$debugbar = null;
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
    // Whoops integration with Monolog
    // Place our custom handler in front of the others, capturing exceptions
    // and logging them, then passing the exception on to the other handlers:
    $whoops->pushHandler(function ($exception, $inspector, $run) use ($log) {
        $log->error($exception->getMessage(), [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    });

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
$env = $config->get('general', 'env');
if (empty($env)) {
    $env = PRODUCTION ? "prod" : "dev";
}
Sentry\init([
    'dsn' => 'https://d7fe1b1c89eb4dd58faf6a213052235b@sentry.io/1810091',
    'release' => file_get_contents(DOCROOT . "/VERSION"),
    'environment' => $env
]);