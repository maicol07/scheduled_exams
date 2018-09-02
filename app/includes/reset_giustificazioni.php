<?php
require_once("../../includes/config.php");

if (isset($argv[1]) and $argv[1] == "--resetgiustificazioni") {
    $reset = $db->prepare("UPDATE users SET giustificazioni_rim = 1");
    $reset->execute();
}