<?php /** @noinspection PhpUndefinedVariableInspection */
require_once("../../includes/config.php");

function base64_to_file($base64_string)
{
    $ext = explode("/", explode(";", $base64_string)[0])[1];
    $data = str_replace('data:image/png;base64,', '', $base64_string);
    $data = str_replace(' ', '+', $data);
    $data = base64_decode($data);
    $path = '../uploads/' . $_POST["user"];
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
        $blank = fopen($path . "/index.html", "w");
        fclose($blank);
    }
    if ($_POST["type"] == "primary") {
        $file = $path . '/profile-img.' . $ext;
    } elseif ($_POST["type"] == "background") {
        $file = $path . '/background-img.' . $ext;
    }
    $GLOBALS["path"] = $file;
    $success = file_put_contents($file, $data);
    return $success;
}

if (isset($_POST["image"])) {
    $success = base64_to_file($_POST["image"]);
    if ($success) {
        $path = str_replace("../", "", $path);
        if ($_REQUEST["type"] == "primary") {
            $query = $db->prepare("UPDATE users SET img = :img WHERE username = :username; ");
        } elseif ($_REQUEST["type"] == "background") {
            $query = $db->prepare("UPDATE users SET backimg = :img WHERE username = :username; ");
        }
        $query->execute(array('img' => $path, 'username' => $_REQUEST["user"]));
        echo $path;
    } else {
        echo "ERR";
    }
}