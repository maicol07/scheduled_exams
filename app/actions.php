<?php

use src\Classroom;

require "../core.php";
switch (post("action")) {
    case "change_language":
        $result = $user->setLanguage(post("lang"));
        break;
    case "create_classroom":
        $classroom = new Classroom($db, $user);
        $classroom->name = post("name");
        $result = $classroom->save();
        break;
    case "delete_classroom":
        $classroom = new Classroom($db, $user, post('id'));
        $result = $classroom->delete();
        break;
    case "join_classroom":
        if (!$db->has("classrooms", ['code' => post('code')])) {
            $result = new \src\Result(null, 'CLASS_DOES_NOT_EXISTS', __("Il codice della classe inserito non è associato ad alcuna classe"));
            break;
        }
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->addUser();
        break;
}
header('Content-Type: application/json; charset=utf-8');
if (!$result->success) {
    $errorinfo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $result->errorinfo);
    $errorcode = $result->errorcode;
    header("HTTP/1.0 505 $errorinfo");
}

echo json_encode($result);