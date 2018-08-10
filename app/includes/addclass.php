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
if (isset($_REQUEST["classe"]) and $_REQUEST["classe"] != "") {
    $nome = $_REQUEST["classe"];
    $select = $db->prepare("SELECT ID FROM classi");
    $select->execute();
    $ids = $select->fetch();
    if (!($ids == "")) {
        while (in_array($code, $ids)) {
            $code = randString(5);
        }
    }
    $query = $db->prepare("INSERT INTO classi(ID, nome) VALUES (:id, :nome);");
    $query->execute(array('id' => $code, 'nome' => $nome));
    $sql = $db->prepare("SELECT classi FROM users WHERE userID = :userID");
    $sql->execute(array('userID' => $_REQUEST["userID"]));
    $userclassi = $sql->fetch();
    $newuserclassi = $userclassi . ", " . $code;
    $query = $db->prepare("UPDATE users SET classi = :idclasse WHERE userID = :userID; ");
    $query->execute(array('idclasse' => $newuserclassi, 'userID' => $_REQUEST["userID"]));
}

echo $code;
?>