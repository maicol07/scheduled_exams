<?php

namespace src;

use Delight\Cookie\Session;
use Medoo\Medoo;

class Auth
{
    private $logged = false;

    /** @var object */
    private $user;

    /** @var Medoo */
    private $db;

    public function __construct($db = null)
    {
        if (!Session::has("user_jwt")) {
            $broker = new Broker("4", "https://account.maicol07.it/sso/auth", "gDbeu6oYB6U0bx9k");
            $this->user = $broker->login();
            Session::set("user_jwt", serialize($this->user));
            $this->logged = true;
        } else {
            $this->user = unserialize(Session::get("user_jwt"));
            $this->logged = true;
        }
        $this->db = $db;
    }

    public function isAuthenticated()
    {
        return $this->logged;
    }

    public function getUsername()
    {
        return $this->user->user_login;
    }

    public function getName()
    {
        return $this->user->user_name;
    }

    public function getFirstName()
    {
        return $this->user->first_name;
    }

    public function getLastName()
    {
        return $this->user->last_name;
    }

    public function getEmail()
    {
        return $this->user->user_email;
    }

    public function getLanguage()
    {
        return !empty($this->user->lang) ? $this->user->lang : $this->db->get("users", [
            "lang"
        ], [
            "username" => $this->user->user_name
        ]);
    }
}