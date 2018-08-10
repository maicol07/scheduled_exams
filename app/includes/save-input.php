<?php
require_once("../../includes/config.php");
if (isset($_POST["text"])) {
    $query = $db->prepare("UPDATE users SET " . $_POST["input"] . " = :value WHERE username = :user");
    $query->execute(array("value" => $_POST["text"], "user" => $_POST["username"]));
    echo "OK";
}