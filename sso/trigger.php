<?php
require_once __DIR__ . '/../core.php';
if ($db->has("users", ['username' => post("old_username")])) {
    $db->update("users", [
        'username' => post("new_username")
    ], [
        'username' => post("old_username")
    ]);
}