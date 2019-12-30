<?php

namespace src;

use Medoo\Medoo;

class Classroom
{
    public $name;
    public $description;
    public $image;
    public $code;
    /* @var Medoo */
    private $db;
    public $id = null;
    private $attributes = [
        'id',
        "name",
        "description",
        "image",
        "users",
        "code",
        "admin",
        "lists"
    ];
    /**
     * @var array|string
     */
    private $users;

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
        $this->users = unserialize($this->users);
        if ($this->users === false) {
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
        $query = $this->db->update("classrooms", ['users' => serialize($this->users)], ['id' => $this->id]);
        if ($query->rowCount()) {
            return new Result(['code' => $this->code, 'name' => $this->name]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo());
        }
    }

    public function removeUser($user = null)
    {
        return $this->manageUser($user, "remove");
    }

    public function save()
    {
        if (empty($this->id)) {
            $code = Utils::generateCode($this->db);
            $query = $this->db->insert("classrooms", [
                "name" => $this->name,
                "code" => $code,
                "users" => serialize([$this->user->getId()]),
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
        ], [
            'admin' => $this->user->getId()
        ]);
    }
}