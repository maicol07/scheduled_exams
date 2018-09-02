<?php
require_once("../../includes/config.php");
if (isset($_POST["text"]) or isset($_POST["img"])) {
    if ($_POST["input"] != "img") {
        $query = $db->prepare("UPDATE liste SET " . $_POST["input"] . " = :value WHERE ID = :idlista");
        $query->execute(array("value" => $_POST["text"], "idlista" => $_POST["idlista"]));
        echo "OK";
    } else {
        function base64_to_file($base64_string)
        {
            $ext = explode("/", explode(";", $base64_string)[0])[1];
            $mime = explode(":", explode(";", $base64_string)[0])[1];
            $data = str_replace('data:' . $mime . ';base64,', '', $base64_string);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $path = '../uploads/liste/' . $_POST["idlista"];
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                $blank = fopen($path . "/index.html", "w");
                fclose($blank);
            }
            $file = $path . '/img.' . $ext;
            $GLOBALS["path"] = $file;
            $success = file_put_contents($file, $data);
            return $success;
        }

        if (isset($_POST["img"])) {
            $success = base64_to_file($_POST["img"]);
            if ($success) {
                $path = str_replace("../", "", $path);
                $query = $db->prepare("UPDATE liste SET img = :img WHERE ID = :idlista; ");
                $query->execute(array('img' => $path, 'idlista' => $_POST["idlista"]));
                echo $path;
            } else {
                echo "ERR";
            }
        }
    }
}