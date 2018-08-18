<?php
require_once("../../includes/config.php");
// Credits to Baba (https://stackoverflow.com/a/15198493/7520280)
function randString($length)
{
    $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#";
    $char = str_shuffle($char);
    for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i++) {
        $rand .= $char{mt_rand(0, $l)};
    }
    return $rand;
}

$code = randString(5);
if (isset($_REQUEST["lista"]) and $_REQUEST["lista"] != "") {
    $nome = $_REQUEST["lista"];
    $select = $db->prepare("SELECT ID FROM liste");
    $select->execute();
    $ids = $select->fetchAll();
    $idlist = array();
    foreach ($ids as $id) {
        array_push($idlist, $id[0]);
    }
    if (count($idlist) !== 0) {
        while (in_array($code, $idlist)) {
            $code = randString(5);
        }
    }
    $query = $db->prepare("INSERT INTO liste(ID, nome, classe, admin) VALUES (:id, :nome, :classe, :admin);");
    $query->execute(array('id' => $code, 'nome' => $nome, 'classe' => $_REQUEST["classid"], 'admin' => $_REQUEST["username"]));
    $query = $db->prepare("UPDATE classi SET liste = CONCAT(liste, ', ', :idlista) WHERE ID = :classid; ");
    $query->execute(array('idlista' => $code, 'classid' => $_REQUEST["classid"]));

}

echo $code;