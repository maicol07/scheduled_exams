<?php
if (!defined("DOCROOT")) {
    define('DOCROOT', '');
}

require_once DOCROOT . "/config/class_loader.php";

use App\Auth;
use App\Config;
use Medoo\Medoo;

$config = new Config(DOCROOT . '/config/config.ini');

if (!defined("PRODUCTION")) {
    if (!empty($config->get('general', 'env'))) {
        switch (strtolower($config->get('general', 'env'))) {
            case 'production':
                define("PRODUCTION", true);
                break;
            default:
                define("PRODUCTION", false);
                break;
        }
    } else if (strpos(BASEURL, "app.scheduledexams.tk")) {
        define("PRODUCTION", true);
    } else {
        define("PRODUCTION", false);
    }
}

require_once DOCROOT . "/config/errors.php";

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
        return App\Utils::get($name);
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
        return App\Utils::post($name);
    }
}


/*
 *
 * Database
 *
 */

$db = new Medoo([
    'database_type' => $config->get('database', 'type'),
    'database_name' => $config->get('database', 'name'),
    'server' => $config->get('database', 'host'),
    'username' => $config->get('database', 'username'),
    'password' => $config->get('database', 'password'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci'
]);

if (isset($debugbar)) {
    $pdo = new DebugBar\DataCollector\PDO\TraceablePDO($db->pdo);
    $debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($pdo));
}

/*
 *
 * Auth config
 *
 */
$user = new Auth($db, isset($noauth) ? $noauth : false);
$logged = $user->isAuthenticated();
// Add user to Sentry if is logged in
if ($logged) {
    Sentry\configureScope(function (Sentry\State\Scope $scope): void {
        global $user;
        $scope->setUser([
            'username' => $user->getUsername(),
            'email' => $user->getEmail()
        ]);
    });
}

/*
 * Cloudinary config
 */
Cloudinary::config([
    "cloud_name" => $config->get('cloudinary', 'cloud_name'),
    "api_key" => $config->get('cloudinary', 'api_key'),
    "api_secret" => $config->get('cloudinary', 'api_secret'),
    "secure" => true
]);