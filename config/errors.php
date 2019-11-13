<?php

/**
 * Sentry
 */
Sentry\init([
    'dsn' => 'https://d7fe1b1c89eb4dd58faf6a213052235b@sentry.io/1810091',
    'release' => file_get_contents(DOCROOT . "/VERSION"),
    'environment' => PRODUCTION ? "prod" : "dev"
]);

/**
 * Whoops (only dev)
 */
if (!PRODUCTION) {
    // Whoops initialization
    $whoops = new Whoops\Run();
    if (Whoops\Util\Misc::isAjaxRequest()) {
        // Enable errors management for AJAX requests
        $whoops->prependHandler(new Whoops\Handler\JsonResponseHandler());
    } else {
        $whoops->prependHandler(new Whoops\Handler\PrettyPageHandler);
    }
    $whoops->register();
}
