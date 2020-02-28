<?php

namespace src;

use App\Auth;
use App\Result;
use App\Utils;
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
    /* @var Classroom */
    private $classroom;

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
     * @var Auth
     */
    private $user;


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
        if (!empty($key) and !empty($value)) {
            $select = $this->db->get("lists", $this->attributes, [$key => $value]);
            foreach ($select as $attribute => $value) {
                $this->$attribute = $value;
            }
            $this->classroom = new Classroom($this->db, $this->user, $this->classroom_id);
            $this->rows = $this->db->select("lists_rows", [
                'id' => [
                    'id',
                    'student_id',
                    'date',
                    'order'
                ]
            ], [
                'list_id' => $this->id,
                'ORDER' => [
                    'order',
                    'date'
                ]
            ]);
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
        if ($this->type == "FROM_START_DATE") {
            /** @noinspection PhpUnhandledExceptionInspection */
            $date = new DateTime($this->start_date);
            $weekdays = unserialize($this->weekdays) ?: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            if (!in_array(strtolower($date->format('l')), $weekdays)) {
                $date = $date->modify("next $weekdays[0]");
            }
            $counter = 0;
            while ($counter < count($students)) {
                foreach ($weekdays as $weekday) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    foreach (range(0, $times - 1) as $i) {
                        $dates[] = $date->format('Y-m-d');
                        $counter += 1;
                    }
                    $date = $date->modify("next $weekday");
                }
            }
        }
        $rows = [];
        $i = 0;
        $date = ($this->type == "FROM_START_DATE") ? $dates[$i] : NULL;
        // Randomize array
        shuffle($students);
        foreach ($students as $student) {
            $student = (object)$student;
            if ($this->type == "FROM_START_DATE") {
                if ($i % $this->quantity === 0) {
                    $date = next($dates);
                }
            }

            $rows[] = [
                'list_id' => $this->id,
                'student_id' => $student->id,
                'date' => $date,
                'order' => $i
            ];
            $i++;
        }
        $this->rows = $rows;
        return $rows;
    }

    public function manageRows($row_id = null, $date = null, $student_id = null, $mode = "add")
    {
        $rows = $this->rows;
        if (empty($this->rows)) {
            $rows = [];
        }
        $row_id = !empty($row_id) ? $row_id : (int)$row_id;
        switch ($mode) {
            case 'add':
                if ($student_id !== "0" and empty($student_id)) {
                    return new Result(null, 'NO_ROW_DATA', __("Non è stato scelto nessuno studente!"));
                }
                $row_id = count($rows);
                if (array_key_exists($row_id, $rows)) {
                    $row_id = end($rows) + 1;
                }
                $order = $this->db->max('lists_rows', 'order [Int]', ['list_id' => $this->id]);
                if ($order == 0 or !empty($order)) {
                    $order += 1;
                } else {
                    $order = 0;
                }
                $rows[$row_id] = [
                    'list_id' => $this->id,
                    'student_id' => $student_id,
                    'date' => $date,
                    'order' => $order
                ];
                $query = $this->db->insert('lists_rows', $rows[$row_id]);
                break;
            case 'edit':
                if ($rows[$row_id]['student_id'] == $student_id and $rows[$row_id]['date'] == $date) {
                    return new Result(null, "SAME_DATA", __("I dati inseriti sono identici a quelli precedenti!"));
                }
                $rows[$row_id]['student_id'] = $student_id;
                $rows[$row_id]['date'] = $date;
                $query = $this->db->update('lists_rows', $rows[$row_id], ['id' => $row_id]);
                break;
            case 'delete':
                unset($rows[$row_id]);
                $student_id = $this->db->get('lists_rows', 'student_id', ['id' => $row_id]);
                $query = $this->db->delete('lists_rows', ['id' => $row_id]);
                break;
            case 'up':
                $student_id = $this->db->get('lists_rows', 'student_id', ['id' => $row_id]);
                $order = $this->db->get('lists_rows', 'order [Int]', ['id' => $row_id]);
                if ($order > 0) {
                    $order -= 1;
                }
                if ($this->db->has('lists_rows', ['list_id' => $this->id, 'order' => $order])) {
                    $this->db->update('lists_rows', ['order' => $order + 1], ['list_id' => $this->id, 'order' => $order, 'id[!]' => $row_id]);
                }
                $query = $this->db->update('lists_rows', ['order' => $order], ['id' => $row_id]);
                break;
            case 'down':
                $student_id = $this->db->get('lists_rows', 'student_id', ['id' => $row_id]);
                $order = $this->db->get('lists_rows', 'order [Int]', ['id' => $row_id]);
                if ($order < $this->db->max('lists_rows', 'order [Int]', ['list_id' => $this->id])) {
                    $order += 1;
                }
                if ($this->db->has('lists_rows', ['list_id' => $this->id, 'order' => $order])) {
                    $this->db->update('lists_rows', ['order' => $order - 1], ['list_id' => $this->id, 'order' => $order, 'id[!]' => $row_id]);
                }
                $query = $this->db->update('lists_rows', ['order' => $order], ['id' => $row_id]);
                break;
        }
        /** @noinspection PhpUndefinedVariableInspection */
        if ((in_array($mode, ['add', 'delete']) and $query->rowCount()) or (in_array($mode, ['add', 'delete']) and $query)) {
            return new Result([
                'code' => $this->code,
                'id' => $this->id,
                'number' => count($rows),
                'student' => $this->classroom->getFilteredStudents(['id' => $student_id])
            ]);
        } else {
            return new Result(null, $query->errorCode(), $query->errorInfo()[2]);
        }
    }

    public function addRow($row_data)
    {
        return $this->manageRows(null, $row_data->date, $row_data->student_id);
    }

    public function editRow($row_id, $row_data)
    {
        return $this->manageRows($row_id, $row_data->date, $row_data->student_id, "edit");
    }

    public function deleteRow($row_id)
    {
        return $this->manageRows($row_id, null, null, "delete");
    }

    public function rowUp($row_id)
    {
        return $this->manageRows($row_id, null, null, "up");
    }

    public function rowDown($row_id)
    {
        return $this->manageRows($row_id, null, null, "down");
    }

    public function save()
    {
        if (empty($this->id)) {
            $method = "insert";
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
            $method = "update";
            $attr = [];
            foreach ($this->attributes as $attribute) {
                $attr[$attribute] = $this->$attribute;
            }
            $query = $this->db->update("lists", $attr, ['id' => $this->id]);
        }
        if (($method == "insert" and $query->rowCount()) or ($method == "update" and $query)) {
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