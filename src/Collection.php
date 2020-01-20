<?php

namespace src;

use Medoo\Medoo;

class Collection
{
    public $id = null;
    public $classroom_id;
    public $name;
    public $description;
    public $image;
    public $type;
    public $start_date;
    public $weekdays;
    public $quantity;
    public $code;
    /* @var Medoo */
    private $db;

    private $attributes = [
        'id',
        'classroom_id',
        "name",
        "description",
        "image",
        "type",
        'start_date' .
        "weekdays",
        'quantity',
        "code",
    ];


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
        if (!empty($id) and $this->db->has("lists", ['id' => (int)$id])) {
            $this->id = $id;
            $key = 'id';
            $value = $id;
        } elseif (!empty($code) and $this->db->has("lists", ['code' => $code])) {
            $this->code = $code;
            $key = 'code';
            $value = $code;
        }
        if (!empty($key)) {
            var_dump($this->attributes);
            header("HTTP/1.0 550 ");
            $select = $this->db->get("lists", $this->attributes, [$key => $value]);
            foreach ($select as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    public function save()
    {
        if (empty($this->id)) {
            $code = Utils::generateCode($this->db);
            $query = $this->db->insert("lists", [
                "name" => $this->name,
                "code" => $code,
                "type" => $this->type,
                'start_date' => $this->start_date,
                "weekdays" => $this->weekdays,
                'quantity' => $this->quantity,
            ]);
            $this->id = $this->db->id();
            $this->code = $code;
        } else {
            $attr = [];
            foreach ($this->attributes as $attribute) {
                $attr[$attribute] = $this->$attribute;
            }
            $query = $this->db->update("lists", $attr, ['id' => $this->id]);
        }
        if ($query->rowCount()) {
            return new Result(['code' => $this->code, 'id' => $this->id]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo()[2]);
        }
    }

    public function delete()
    {
        var_dump($this->id);
        header("HTTP/1.0 550 " . (string)$this->id);
        exit();
        if (empty($this->id)) {
            return new Result(null, "NO_ID", "NO_ID");
        }
        $code = $this->code;
        $query = $this->db->delete("lists", [
            'id' => $this->id
        ]);
        if ($query->rowCount()) {
            return new Result(['code' => $code]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo());
        }
    }

    /**
     * Get lists from database
     *
     * @param null|int $classroom_id If provided, search will be limited to this classroom only
     * @return array|bool
     */
    public function getLists($classroom_id = null)
    {
        return $this->db->select("lists", [
            'id [Int]',
            'name',
            'description',
            'image',
            'code',
            'type'
        ], $classroom_id ? [
            'classroom_id' => $classroom_id
        ] : []
        );
    }
}