<?php

namespace src;

use Medoo\Medoo;

class Classroom
{
    public $id = null;
    public $name;
    public $description;
    public $image;
    public $code;
    /* @var Medoo */
    private $db;

    private $attributes = [
        'id',
        "name",
        "description",
        "image",
        "users",
        "code",
        "admin",
        "lists",
        'students'
    ];
    /**
     * @var string
     */
    public $users;
    /* @var string (json) Example: {student1: {name: ..., surname: ..., user_id: ...}, ...} */
    public $students;
    public $admin;

    /**
     * Classroom constructor.
     * @param $db Medoo
     * @param $user Auth
     * @param null|int $id
     * @param null|string $code
     */
    public function __construct($db, $user, $id = null, $code = null)
    {
        $this->db = $db;
        $this->user = $user;
        if (!empty($id) and $this->db->has("classrooms", ['id' => (int)$id])) {
            $this->id = $id;
            $key = 'id';
            $value = $id;
        } elseif (!empty($code) and $this->db->has("classrooms", ['code' => $code])) {
            $this->code = $code;
            $key = 'code';
            $value = $code;
        }
        if (!empty($key)) {
            $select = $this->db->get("classrooms", $this->attributes, [$key => $value]);
            foreach ($select as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    public function addUser($user = null)
    {
        return $this->manageUser($user);
    }

    private function manageUser($user = null, $mode = "add")
    {
        $this->users = json_decode($this->users);
        if (empty($this->users)) {
            $this->users = [];
        }
        if (empty($user)) {
            $user = $this->user->getId();
        }
        switch ($mode) {
            case 'add':
                if (in_array($user, $this->users)) {
                    return new Result(null, 'ALREADY_EXISTS', __("L'utente fa già parte della classe"));
                }
                $this->users[] = $user;
                break;
            case 'remove':
                unset($this->users[array_search($user, $this->users)]);
                break;
        }
        $this->users = json_encode($this->users);
        return $this->save();
    }

    public function removeUser($user = null)
    {
        return $this->manageUser($user, "remove");
    }

    public function addStudent($student)
    {
        return $this->manageStudent($student);
    }

    private function manageStudent($student, $mode = "add", $new_student_info = null)
    {
        $students = json_decode($this->students, true);
        if (empty($this->students)) {
            $students = [];
        }
        $student_id = (int)$student;
        switch ($mode) {
            case 'add':
                if (!empty($students) and in_array($student, array_column(array_values($this->getStudents()), 'name'))) {
                    return new Result(null, 'ALREADY_EXISTS', __("Lo studente fa già parte della classe"));
                }
                $student_id = count($students);
                $students[$student_id] = [
                    'name' => $student,
                    'user_id' => 0
                ];
                break;
            case 'edit':
                if ($students[$student_id]['name'] == $new_student_info) {
                    return new Result(null, "SAME_NAME", __("Il nome inserito è identico a quello precedente!"));
                }
                $students[$student_id]['name'] = $new_student_info;
                break;
            case 'link':
                $students[$student_id]['user_id'] = $new_student_info;
                break;
            case 'unlink':
                $students[$student_id]['user_id'] = 0;
                break;
            case 'remove':
                unset($students[$student_id]);
                break;
        }
        if ($mode != 'remove') {
            $students[$student_id] = (object)$students[$student_id];
        }
        $this->students = json_encode($students);
        return $this->save();
    }

    public function editStudent($student, $new_name)
    {
        return $this->manageStudent($student, "edit", $new_name);
    }

    public function linkStudent($student, $user_id)
    {
        return $this->manageStudent($student, "link", $user_id);
    }

    public function unlinkStudent($student)
    {
        return $this->manageStudent($student, "unlink");
    }

    public function removeStudent($student)
    {
        return $this->manageStudent($student, "remove");
    }

    public function save()
    {
        if (empty($this->id)) {
            $code = Utils::generateCode($this->db);
            $query = $this->db->insert("classrooms", [
                "name" => $this->name,
                "code" => $code,
                "users" => json_encode([$this->user->getId()]),
                "admin" => $this->user->getId(),
            ]);
            $this->id = $this->db->id();
            $this->code = $code;
        } else {
            $attr = [];
            foreach ($this->attributes as $attribute) {
                $attr[$attribute] = $this->$attribute;
            }
            $query = $this->db->update("classrooms", $attr, ['id' => $this->id]);
        }
        if ($query->rowCount()) {
            return new Result(['code' => $this->code, 'id' => $this->id]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo()[2]);
        }
    }

    public function delete()
    {
        if (empty($this->id)) {
            return new Result(null, "NO_ID", "NO_ID");
        }
        $code = $this->code;
        $query = $this->db->delete("classrooms", [
            'id' => $this->id
        ]);
        if ($query->rowCount()) {
            return new Result(['code' => $code]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo());
        }
    }

    /**
     * Get classrooms from database
     *
     * @return array|bool
     */
    public function getClassrooms()
    {
        return $this->db->select("classrooms", [
            'id',
            'name',
            'description',
            'image',
            'users',
            'code',
            'admin'
        ],
            Medoo::raw('WHERE `admin` = ' . $this->user->getId() . ' OR JSON_CONTAINS(users, ' . $this->user->getId() . ')')
        );
    }

    public function getStudents()
    {
        $students = json_decode($this->students);
        $list = null;
        if (!empty($this->students)) {
            $list = [];
            foreach ($students as $student_id => $student) {
                $value = ['name' => $student->name, 'username' => '', 'image' => ROOTDIR . '/app/assets/img/user.svg', 'id' => $student_id];
                $users = json_decode($this->users, true);
                if (!empty($users) and in_array($student->user_id, $users)) {
                    $value['username'] = $this->db->get('users', 'username', ['id' => $student->user_id]);
                    //TODO 1.1: $value['image'] = (new Gravatar())->avatar($this->db->get('users', 'email', ['id' => $student->user_id]));
                }
                $list[] = $value;
            }
        }
        return $list;
    }

    public function getUsers()
    {
        $users = json_decode($this->users);
        $list = null;
        if (!empty($this->users)) {
            $list = [];
            foreach ($users as $user) {
                $username = $this->db->get('users', 'username', [
                    'id' => $user
                ]);
                $list[$user] = $username;
            }
        }
        return $list;
    }
}