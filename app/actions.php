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
        $result->name = $classroom->name;
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
    case "leave_classroom":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->removeUser();
        break;
    case "add_classroom_student":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->addStudent(post('name'));
        break;
    case "edit_classroom_student":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->editStudent(post('student_id'), post('student_name'));
        break;
    case "link_classroom_student":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->linkStudent(post('student_id'), post('user_id'));
        break;
    case "unlink_classroom_student":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->unlinkStudent(post('student_id'));
        break;
    case "delete_classroom_student":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = $classroom->removeStudent(post('student_id'));
        break;
    case "get_classroom_students":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = new Result(['students' => $classroom->getStudents()]);
        break;
    case "get_classroom_users":
        $classroom = new Classroom($db, $user, null, post('code'));
        $result = new Result(['users' => $classroom->getUsers()]);
        break;
}
header('Content-Type: application/json; charset=utf-8');
if (!$result->success) {
    if (!PRODUCTION and !empty($db->last())) {
        $result->errorinfo .= (" - {$db->last()}");
    }
    $errorinfo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $result->errorinfo);
    $errorcode = $result->errorcode;
    header("HTTP/1.0 550 $errorinfo");
}

echo json_encode($result);