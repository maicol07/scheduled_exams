<?php
require_once("../../includes/config.php");
language("app");
if (isset($_POST['classid']) and $_POST["classid"] !== "") {
    $errors = [];
    $query = $db->prepare("SELECT img FROM classi WHERE ID = :id; ");
    $query->execute(array('id' => $_POST["classid"]));
    $r = $query->fetch()[0];
    if ($r != null and $r != "") {
        $file = "../" . $query->fetch()[0];
        if (file_exists($file)) {
            if (!is_writeable($file)) {
                $errors[] = _("L'immagine della classe è protetta da scrittura. Impossibile eliminare la classe.");
            }
            if (empty($errors)) {
                $result = unlink($file);
                if ($result == false) {
                    $errors[] = _("Impossibile eliminare l'immagine della classe. Impossibile eliminare la classe.");
                }
            }
        }
    }
    if ($errors) {
        print_r($errors);
    } else {
        $query = $db->prepare("DELETE FROM classi WHERE ID = :id; ");
        $query->execute(array('id' => $_POST["classid"]));
        echo "OK";
    }
}