<?php
require_once("../../includes/config.php");

if (isset($_REQUEST["username"]) and $_REQUEST["username"] != "") {
    $select = $db->prepare("SELECT nome, cognome, genere, img FROM users WHERE username = :username");
    $select->execute(array('username' => $_REQUEST["username"]));
    $info = $select->fetch();
    echo $info["nome"] . ", " . $info["cognome"] . ", " . $info["genere"] . ", " . $info["img"];
}