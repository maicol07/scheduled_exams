<?php
require_once("../../includes/config.php");
if (isset($_POST["password"])) {
    $select = $db->prepare("SELECT password FROM users WHERE username = :usr");
    $select->execute(array("usr" => $_POST["username"]));
    $hash = $select->fetch()[0];
    if ($user->password_verify($_POST["password"], $hash)) {
        $query = $db->prepare("DELETE FROM users WHERE username = :usr");
        $query->execute(array("usr" => $_POST["username"]));
        echo "OK";
    } else {
        echo "PSW_ERR";
    }
}