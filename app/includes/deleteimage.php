<?php
require_once("../../includes/config.php");
language("app");
if (isset($_REQUEST['type']) and $_REQUEST["type"] !== "") {
    $errors = [];
    if ($_REQUEST["type"] == "primary") {
        $query = $db->prepare("SELECT img FROM users WHERE username = :username; ");
    } elseif ($_REQUEST["type"] == "background") {
        $query = $db->prepare("SELECT backimg FROM users WHERE username = :username; ");
    }
    $query->execute(array('username' => $_REQUEST["user"]));
    $file = "../" . $query->fetch()[0];
    if (!file_exists($file)) {
        $errors[] = _("Non esiste una immagine associata al tuo account!");
    }
    if (!is_writeable($file)) {
        $errors[] = _("File protetto da scrittura. Impossibile eliminare l'immagine.");
    }
    if (empty($errors)) {
        $result = unlink($file);
        if ($result == false) {
            $errors[] = _("Impossibile eliminare il file.");
        }
    }
    if ($errors) {
        print_r($errors);
    } else {
        if ($_REQUEST["type"] == "primary") {
            $query = $db->prepare("UPDATE users SET img = :img WHERE username = :username; ");
        } elseif ($_REQUEST["type"] == "background") {
            $query = $db->prepare("UPDATE users SET backimg = :img WHERE username = :username; ");
        }
        $query->execute(array('img' => "", 'username' => $_REQUEST["user"]));
        echo "OK";
    }
}