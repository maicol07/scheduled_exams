<?php

use src\Classroom;
use src\Result;

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
    case "update_classroom":
        $classroom = new Classroom($db, $user, null, post('code'));
        // Image
        $default = strpos(post('image'), 'exams.svg');
        if (!$default) {
            $imgdata = base64_decode(post('image'));
            $f = finfo_open();
            $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
            $split = explode('/', $mime_type);
            $ext = $split[1];
            $cloudinary = Cloudinary\Uploader::upload(post('image'), [
                'folder' => 'scheduled_exams/classes',
                'public_id' => $classroom->code . ".$ext",
                'overwrite' => true
            ]);
            $classroom->image = $cloudinary['secure_url'];
        } elseif ($default and post('name') == $classroom->name and post('description') == $classroom->description) {
            $result = new Result(null, "EQUALS", __("Le informazioni immesse sono identiche a quelle precedenti!"));
            break;
        }
        $classroom->name = post('name');
        $classroom->description = post('description');
        $result = $classroom->save();
        break;
    case "delete_classroom":
        $classroom = new Classroom($db, $user, post('id'));
        $result = $classroom->delete();
        break;
    case "join_classroom":
        if (!$db->has("classrooms", ['code' => post('code')])) {
            $result = new Result(null, 'CLASS_DOES_NOT_EXISTS', __("Il codice della classe inserito non è associato ad alcuna classe"));
            break;
        }
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->addUser();
        break;
}
header('Content-Type: application/json; charset=utf-8');
if (!$result->success) {
    var_dump($db->last());
    $errorinfo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $result->errorinfo);
    $errorcode = $result->errorcode;
    header("HTTP/1.0 550 $errorinfo");
}

echo json_encode($result);