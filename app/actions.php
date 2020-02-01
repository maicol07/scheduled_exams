<?php

use src\Classroom;
use src\Collection;
use src\Result;

require __DIR__ . "/../core.php";
switch (post("action")) {
    case "change_language":
        $result = $user->setLanguage(post("lang"));
        break;
    // CLASSROOM
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
            $cloudinary = Cloudinary\Uploader::upload(post('image'), [
                'folder' => 'scheduled_exams/classes/' . $classroom->code,
                'public_id' => 'image',
                'overwrite' => true
            ]);
            $classroom->image = $cloudinary['secure_url'];
        } elseif (($default or post('image') == $list->image) and post('name') == $classroom->name and post('description') == $classroom->description) {
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

    // LISTS
    case 'create_list':
        $list = new Collection($db, $user);
        $list->name = post('name');
        $list->type = post('type');
        $list->start_date = (new DateTime(post('start_date')))->format('Y-m-d');
        $list->weekdays = serialize(post('weekdays'));
        $list->quantity = post('quantity');
        // Link to classroom
        $classroom = new Classroom($db, $user, null, post('classroom_code'));
        $list->classroom_id = $classroom->id;
        $result = $list->save();
        $result->name = $list->name;
        break;
    case "update_list":
        $list = new Collection($db, $user, null, post('code'));
        // Image
        $default = strpos(post('image'), 'list.svg');
        if (!$default) {
            $cloudinary = Cloudinary\Uploader::upload(post('image'), [
                'folder' => 'scheduled_exams/classes/' . (new Classroom($db, $user, $list->classroom_id))->code . '/lists/',
                'public_id' => $list->code,
                'overwrite' => true
            ]);
            $list->image = $cloudinary['secure_url'];
        } elseif (($default or post('image') == $list->image) and post('name') == $list->name and post('description') == $list->description) {
            $result = new Result(null, "EQUALS", __("Le informazioni immesse sono identiche a quelle precedenti!"));
            break;
        }
        $list->name = post('name');
        $list->description = post('description');
        $result = $list->save();
        break;
    case 'delete_list':
        $list = new Collection($db, $user, post('id'));
        $result = $list->delete();
        $classroom = new Classroom($db, $user, $list->classroom_id);
        $result->classroom_code = $classroom->code;
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