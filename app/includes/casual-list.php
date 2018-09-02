<?php
require_once("../../includes/config.php");
if (isset($_POST["idlista"]) or isset($_POST["idlista"])) {
    $classusers = explode(", ", $_POST["users"]);
    $contenitore = array();
    $contenuto = array("giornata" => "", "giustificato" => "N");
    foreach (range(0, count($classusers)) as $value) {
        $selecteduser = $classusers[array_rand($classusers)];
        $contenitore[$selecteduser] = $contenuto;
    }
    $query = $db->prepare("UPDATE liste SET contenuto = :value, type = :type WHERE ID = :idlista");
    $query->execute(array("value" => serialize($contenitore), "type" => $_POST["tipo"], "idlista" => $_POST["idlista"]));
    echo "OK";
}