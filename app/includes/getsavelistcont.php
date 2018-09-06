<?php
require_once("../../includes/config.php");
language("app");
if (isset($_POST["idlista"])) {
    $errors = false;
    $username = $_POST["user"];
    $listinfo = $user->get_list_data($_POST["idlista"]);
    $contenuto = unserialize($listinfo["contenuto"]);
    $data = $_POST["text"];
    $dataf = date("Y/m/d", strtotime($data));
    if (count($contenuto) !== 1) {
        switch ($id = array_search($username, array_keys($contenuto))) {
            case 0:
                if ($dataf >= date("Y/m/d", strtotime($contenuto[array_keys($contenuto)[$id + 1]]["giornata"]))) {
                    $contenuto[$username]["giornata"] = $data;
                } else {
                    $errors = true;
                    echo _("La data inserita è precedente rispetto a quella della persona successiva.");
                }
                break;
            case count($contenuto) - 1:
                if ($dataf <= date("Y/m/d", strtotime($contenuto[$id - 1]["giornata"]))) {
                    $contenuto[$username]["giornata"] = $data;
                } else {
                    $errors = true;
                    echo _("La data inserita è precedente rispetto a quella della persona successiva.");
                }
                break;
            default:
                if ($dataf >= date("Y/m/d", strtotime($contenuto[$id + 1]["giornata"])) and $dataf <= date("Y/m/d", strtotime($contenuto[$id - 1]["giornata"]))) {
                    $contenuto[$username]["giornata"] = $data;
                } else {
                    $errors = true;
                    echo _("La data inserita è precedente rispetto a quella della persona successiva.");
                }
                break;
        }
    } else {
        $contenuto[$username]["giornata"] = $data;
    }
    if ($errors != true) {
        $query = $db->prepare("UPDATE liste SET contenuto = :value WHERE ID = :idlista");
        $query->execute(array("value" => serialize($contenuto), "idlista" => $_POST["idlista"]));
        echo "OK\n";
        echo serialize($contenuto);
    }
}