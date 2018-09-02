<?php
require_once("../../includes/config.php");
if (isset($_POST["idlista"])) {
    $username = $_POST["user"];
    $listinfo = $user->get_list_data($_POST["idlista"]);
    $contenuto = unserialize($listinfo["contenuto"]);
    $contenuto[$username]["giustificato"] = $_POST["value"];
    $query = $db->prepare("UPDATE liste SET contenuto = :value WHERE ID = :idlista");
    $query->execute(array("value" => serialize($contenuto), "idlista" => $_POST["idlista"]));
    if ($_POST["value"] == "S") {
        $query = $db->prepare("UPDATE users SET giustificazioni_rim = (giustificazioni_rim - 1) WHERE username = :username");
    } else {
        $query = $db->prepare("UPDATE users SET giustificazioni_rim = (giustificazioni_rim + 1) WHERE username = :username");
    }
    $query->execute(array("username" => $_POST["user"]));
    echo "OK\n";
    echo serialize($contenuto);
    echo "\n";
    echo $user->get_user_data($_POST["user"])["giustificazioni_rim"];
}