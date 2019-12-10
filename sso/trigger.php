<?php
require_once '../core.php';
if ($db->has("users", ['username' => post("old_username")])) {
    $db->update("users", [
        'username' => post("new_username")
    ], [
        'username' => post("old_username")
    ]);
}