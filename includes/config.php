<?php
ob_start();
session_start();

// Impostare il fuso orario
date_default_timezone_set('Europe/Rome');

// Credenziali database
// IMPOSTARE L'HOST DEL DATABASE, IL NOME UTENTE E LA PASSWORD QUI!!
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'testintp');

// Indirizzo applicazione
define('DIR', 'https://app.interrogazioniprogrammate.tk/');
define('SITEEMAIL', 'noreply@interrogazioniprogrammate.tk');
define("SITETITLE", "Interrogazioni Programmate");

try {

    // crea connessione PDO al database
    $db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // mostra errore
    echo '<p>' . $e->getMessage() . '</p>';
    exit;
}

// Includi la classe utente, passala nella connessione al database
$file = "classes";
while (!file_exists($file)) {
    $file = "../" . $file;
}
include($file . '/user.php');
include($file . '/phpmailer/mail.php');

$user = new User($db);
global $db;

// Installazione lingua
function language($domain)
{
    $dir = "locale";
    while (!file_exists($dir)) {
        $dir = "../" . $dir;
    }
    if (isset($_GET["lang"]) and $_GET["lang"] != "") {
        $locale = $_GET["lang"];
    } else {
        if (isset($_SESSION["username"])) {
            $langdb = $GLOBALS["db"]->prepare("SELECT lang FROM users WHERE username = :usr");
            $langdb->execute(array("usr" => $_SESSION["username"]));
            $langdb = $langdb->fetch()[0];
        }
        if (isset($langdb) and ($langdb != null or $langdb != "")) {
            $locale = $langdb;
        } else {
            $languages = array_filter(scandir($dir), function ($dir) {
                return strpos($dir, '.') === false;
            });
            $locale = locale_lookup($languages, locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']), true, 'en_US');
        }
    }
    if (defined('LC_MESSAGES')) {
        setlocale(LC_MESSAGES, $locale); // Linux
        bindtextdomain($domain, "./" . $dir);
    } else {
        putenv("LC_ALL={$locale}"); // windows
        bindtextdomain($domain, ".\{$dir}");
    }
    textdomain($domain);
    return $locale;
}