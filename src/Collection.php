<?php

namespace src;

use DateTime;
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
    public $rows;
    /* @var Medoo */
    private $db;

    private $attributes = [
        'id',
        'classroom_id',
        "name",
        "description",
        "image",
        "type",
        'start_date',
        "weekdays",
        'quantity',
        "code",
    ];


    /**
     * Collection constructor.
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
            $select = $this->db->get("lists", $this->attributes, [$key => $value]);
            foreach ($select as $attribute => $value) {
                $this->$attribute = $value;
            }
            $this->rows = $this->db->select("lists_rows", ['id', 'student_id', 'date'], ['list_id' => $this->id, 'ORDER' => 'date']);
        }
    }

    public function generateRows()
    {
        $classroom = new Classroom($this->db, $this->user, $this->classroom_id);
        $students = $classroom->getStudents();
        if (empty($this->quantity)) {
            $this->quantity = 1;
        }
        $times = ceil(count($students) / $this->quantity);
        $dates = [];
        if (!empty($this->start_date)) {
            $start_date = new DateTime($this->start_date);
            $dates[] = $start_date->format('Y-m-d');
            $weekdays = unserialize($this->weekdays) ?: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            foreach (range(0, $times) as $i) {
                foreach ($weekdays as $weekday) {
                    $date = $start_date->modify("next $weekday");
                    $dates[] = $date->format('Y-m-d');
                }
            }
        }
        $rows = [];

        $i = 0;
        $date = !empty($this->start_date) ? $dates[$i] : NULL;
        // Randomize array
        shuffle($students);
        foreach ($students as $student_id) {
            if ($i > $times) {
                break;
            }
            if (!empty($this->start_date)) {
                if ($i % $this->quantity === 0) {
                    $date = $dates[$i];
                }
            }

            $rows[] = [
                'list_id' => $this->id,
                'student_id' => $student_id,
                'date' => $date
            ];
        }
        $this->rows = $rows;
        return $rows;
    }

    public function manageRows($row_id, $date = null, $mode = "a")
    {

    }

    public function addRow($row_id, $row_data)
    {
        $this->manageRows($row_id, $row_data->date);
    }

    public function editRow($row_id, $row_data)
    {
        $this->manageRows($row_id, $row_data->date, "e");
    }

    public function deleteRow($row_id)
    {
        $this->manageRows($row_id, null, "d");
    }

    public function save()
    {
        if (empty($this->id)) {
            $code = Utils::generateCode($this->db);
            $query = $this->db->insert("lists", [
                "name" => $this->name,
                'classroom_id' => (int)$this->classroom_id,
                "code" => $code,
                "type" => $this->type,
                'start_date' => $this->start_date,
                "weekdays" => $this->weekdays,
                'quantity' => $this->quantity,
            ]);
            $this->id = $this->db->id();
            $this->code = $code;
            if ($this->type != "MANUAL") {
                $this->db->insert('lists_rows', $this->generateRows());
            }
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
        if (empty((int)$this->id)) {
            return new Result(null, "NO_ID", "NO_ID");
        }
        $code = $this->code;
        $query = $this->db->delete("lists", [
            'id' => (int)$this->id
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
            'classroom_id' => $classroom_id,
            'ORDER' => 'name'
        ] : [
            'ORDER' => 'name'
        ]
        );
    }
}