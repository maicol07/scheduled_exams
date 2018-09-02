<?php
require_once "../../includes/config.php";
if (isset($_POST["email_list"]) and $_POST["email_list"] !== "") {
    $emails = explode(", ", $_POST["email_list"]);
    $contenitore = array();
    $contenuto = array("giornata" => "", "giustificato" => "N");
    foreach ($emails as $email) {
        $userslist = $user->get_class_data($_POST["classid"])["users"];
        foreach (explode(", ", $userslist) as $username) {
            $select = $db->prepare("SELECT email, username FROM users WHERE username = :uname");
            $select->execute(array("uname" => $username));
            $email_list = $select->fetchAll();
            $email_ar = [];
            foreach ($email_list as $e) {
                $email_ar[$e[1]] = $e[0];
            }
        }
        if (in_array($email, array_values($email_ar))) {
            $contenitore[array_keys($email_ar)[array_search($email, array_values($email_ar))]] = $contenuto;
            $query = $db->prepare("UPDATE liste SET contenuto = :cont, mode = 'manual' WHERE ID = :id");
            $query->execute(array("cont" => serialize($contenitore), "id" => $_POST["idlista"]));
            echo "OK";
        }
    }
}