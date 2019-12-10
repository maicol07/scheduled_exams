<?php

require "../core.php";
switch (post("action")) {
    case "change_language":
        echo json_encode($user->setLanguage(post("lang")));
        break;
}