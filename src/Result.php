<?php


namespace src;


class Result
{
    public $errorcode;
    public $errorinfo;
    public $success = true;

    public function __construct(array $data = null, $errorcode = null, $errorinfo = null)
    {
        if (is_array($data) and !empty($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->errorcode = $errorcode;
        $this->errorinfo = $errorinfo;
        if (!empty($this->errorcode) or !empty($this->errorinfo)) {
            $this->success = false;
        }
    }

    public function getError()
    {
        return $this->errorcode . ' - ' . $this->errorinfo;
    }
}