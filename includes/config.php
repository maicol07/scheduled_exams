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
define('DIR', 'https://interrogazioniprogrammate.tk/');
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
?>