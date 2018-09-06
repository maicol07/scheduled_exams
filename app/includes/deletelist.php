<?php
require_once("../../includes/config.php");
language("app");
if (isset($_POST['listid']) and $_POST["listid"] !== "") {
    $errors = [];
    $query = $db->prepare("SELECT img FROM liste WHERE ID = :id; ");
    $query->execute(array('id' => $_POST["listid"]));
    $r = $query->fetch()[0];
    if ($r != null and $r != "") {
        $file = "../" . $query->fetch()[0];
        if (file_exists($file)) {
            if (!is_writeable($file)) {
                $errors[] = _("L'immagine della lista è protetta da scrittura. Impossibile eliminare la lista.");
            }
            if (empty($errors)) {
                $result = unlink($file);
                if ($result == false) {
                    $errors[] = _("Impossibile eliminare l'immagine della lista. Impossibile eliminare la lista.");
                }
            }
        }
    }
    if ($errors) {
        print_r($errors);
    } else {
        $query = $db->prepare("DELETE FROM liste WHERE ID = :id; ");
        $query->execute(array('id' => $_POST["listid"]));
        echo "OK";
    }
}