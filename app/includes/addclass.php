<?php
require_once("../../includes/config.php");
// Credits to Baba (https://stackoverflow.com/a/15198493/7520280)
function randString($length)
{
    $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $char = str_shuffle($char);
    for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i++) {
        $rand .= $char{mt_rand(0, $l)};
    }
    return $rand;
}

$code = randString(5);
if (isset($_REQUEST["classe"]) and $_REQUEST["classe"] != "") {
    $nome = $_REQUEST["classe"];
    $select = $db->prepare("SELECT ID FROM classi");
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
    $query = $db->prepare("INSERT INTO classi(ID, nome, users, admin) VALUES (:id, :nome, :users, :users);");
    $query->execute(array('id' => $code, 'nome' => $nome, 'users' => $_REQUEST["username"]));
    $query = $db->prepare("UPDATE users SET classi = CONCAT(classi, ', ', :idclasse), adminclassi = CONCAT(adminclassi, ', ', :idclasse) WHERE username = :username; ");
    $query->execute(array('idclasse' => $code, 'username' => $_REQUEST["username"]));

}

echo $code;
?>