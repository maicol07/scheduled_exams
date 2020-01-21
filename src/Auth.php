<?php

namespace src;

use Delight\Cookie\Cookie;
use Delight\Cookie\Session;
use Medoo\Medoo;
use function Sentry\captureMessage;

class Auth
{
    private $logged = false;

    /** @var object */
    private $user;

    /** @var Medoo */
    private $db;
    /**
     * @var int
     */
    private $id;

    /**
     * Auth constructor
     * .
     * @param null|Medoo $db
     */
    public function __construct($db = null)
    {
        $broker = new Broker("4", "https://account.maicol07.it/sso/auth", "gDbeu6oYB6U0bx9k");
        $jwt_cookie = new Cookie("user_jwt");
        if (!Cookie::exists("user_jwt")) {
            $this->user = $broker->login();
            $jwt_cookie->setValue(serialize($this->user));
            if (!$jwt_cookie->save()) {
                captureMessage("JWT cookie save failed for user {$this->user->user_login}");
            }
            $this->logged = true;
        } else {
            $this->user = unserialize(Cookie::get("user_jwt"));
            $this->logged = true;
        }

        $this->db = $db;
        if (!Session::has('user_id')) {
            if (!empty($renew = $broker->needsRefresh($this->user))) {
                $this->user = $renew;
                Session::set("user_jwt", serialize($this->user));
            }
            if (!$this->db->has("users", ['username' => $this->getUsername()])) {
                $this->db->insert("users", [
                    'username' => $this->getUsername(),
                    'locale' => $this->getLanguage()
                ]);
                $this->id = $this->db->id();
            } else {
                $this->id = $this->db->get("users", "id [Int]", ['username' => $this->getUsername()]);
            }
            Session::set('user_id', $this->id);
        } else {
            $this->id = Session::get('user_id');
        }
    }

    public function isAuthenticated()
    {
        return $this->logged;
    }

    public function getId()
    {
        return $this->id;
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
        if ($this->db->has("users", [
            'locale[!]' => ''
        ], [
            'id' => $this->id
        ])) {
            return $this->db->get("users", "locale", [
                "id" => $this->id
            ]);
        }
        return $this->user->lang;
    }

    /**
     * Sets a new language/locale for the current user
     *
     * @param string $lang
     * @return Result
     */
    public function setLanguage($lang)
    {
        $update = $this->db->update("users", [
            "locale" => $lang
        ], [
            "id" => $this->id
        ]);
        if ($update->rowCount()) {
            return new Result();
        } else {
            return new Result(null, $update->errorCode(), $update->errorInfo());
        }
    }
}