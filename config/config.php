<?php
/*
 *
 * General Constants
 *
 */
if (!defined("PRODUCTION")) {
    if (BASEURL == "app.scheduledexams.tk") {
        define("PRODUCTION", true);
    } else {
        define("PRODUCTION", false);
    }
}


if (defined("DOCROOT")) {
    require_once DOCROOT . "/config/class_loader.php";
    require_once DOCROOT . "/config/errors.php";
} else {
    require_once "class_loader.php";
    require_once "errors.php";
}

/*
 * Shortcut functions
 */
if (!function_exists("get")) {
    /**
     * Returns a validated GET request parameter
     *
     * @param string $name
     * @return string|bool
     */
    function get($name)
    {
        return src\Utils::get($name);
    }
}

if (!function_exists("post")) {
    /**
     * Returns a validated POST request parameter
     *
     * @param string $name
     * @return string|bool
     */
    function post($name)
    {
        return src\Utils::post($name);
    }
}


/*
 *
 * Database
 *
 */

use Medoo\Medoo;
use src\Auth;

if (defined("DOCROOT")) {
    require_once DOCROOT . "/config/db.php";
} else {
    require_once "db.php";
}
$db = new Medoo([
    'database_type' => $db_type,
    'database_name' => $db_name,
    'server' => $db_host,
    'username' => $db_user,
    'password' => $db_psw,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci'
]);

/*
 *
 * Auth config
 *
 */
$user = new Auth($db);
$logged = $user->isAuthenticated();
// Add user to Sentry if is logged in
if ($logged) {
    Sentry\configureScope(function (Sentry\State\Scope $scope): void {
        global $user;
        $scope->setUser([
            'email' => $user->getEmail()
        ]);
    });
}