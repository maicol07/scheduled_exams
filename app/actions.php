<?php

use App\Result;
use src\Classroom;
use src\Collection;

require __DIR__ . "/../core.php";

$classroom = new Classroom($db, $user, post('id'), post('code'));
$list = new Collection($db, $user, post('id'), post('code'));

switch (post("action")) {
    case "change_language":
        $result = $user->setLanguage(post("lang"));
        break;
    // CLASSROOM
    case "create_classroom":
        $classroom->name = post("name");
        $result = $classroom->save();
        $result->name = $classroom->name;
        break;
    case "update_classroom":
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
        $result = $classroom->delete();
        break;
    case "join_classroom":
        if (!$db->has("classrooms", ['code' => post('code')])) {
            $result = new Result(null, 'CLASS_DOES_NOT_EXISTS', __("Il codice della classe inserito non è associato ad alcuna classe"));
            break;
        }
        $result = $classroom->addUser();
        break;
    case "leave_classroom":
        $result = $classroom->removeUser();
        break;
    case "add_classroom_student":
        $result = $classroom->addStudent(post('name'));
        break;
    case "edit_classroom_student":
        $result = $classroom->editStudent(post('student_id'), post('student_name'));
        break;
    case "link_classroom_student":
        $result = $classroom->linkStudent(post('student_id'), post('user_id'));
        break;
    case "unlink_classroom_student":
        $result = $classroom->unlinkStudent(post('student_id'));
        break;
    case "add_classroom_admin":
        $result = $classroom->addAdmin(post('student_id'));
        break;
    case "revoke_classroom_admin":
        $result = $classroom->revokeAdmin(post('student_id'));
        break;
    case "delete_classroom_student":
        $result = $classroom->removeStudent(post('student_id'));
        break;
    case "get_classroom_students":
        if (!empty(post('get_as_list'))) {
            $classroom = new Classroom($db, $user, $list->classroom_id);
        }
        $result = new Result(['students' => $classroom->getStudents()]);
        break;
    case "get_classroom_users":
        $result = new Result(['users' => $classroom->getUsers()]);
        break;

    // LISTS
    case 'create_list':
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
        $result = $list->delete();
        $classroom = new Classroom($db, $user, $list->classroom_id);
        /** @noinspection PhpUndefinedFieldInspection */
        $result->classroom_code = $classroom->code;
        break;
    case 'add_row_list':
        $result = $list->addRow((object)[
            'student_id' => post('student_id'),
            'date' => post('date')
        ]);
        break;
    case 'edit_row_list':
        $result = $list->editRow(post('row_id'), (object)['student_id' => post('student_id'), 'date' => post('date')]);
        break;
    case 'delete_row_list':
        $result = $list->deleteRow(post('row_id'));
        break;
    case 'order_row_list':
        switch (post('direction')) {
            case 'up':
                $result = $list->rowUp(post('row_id'));
                break;
            case 'down':
                $result = $list->rowDown(post('row_id'));
                break;
        }
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