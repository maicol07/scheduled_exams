<?php

namespace App;

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
     * @param bool $noauth
     */
    public function __construct($db = null, $noauth = false)
    {
        $config = new Config(DOCROOT . '/config/config.ini');
        $broker = new Broker($config->get('sso', 'broker_id'), $config->get('sso', 'server_url'), $config->get('sso', 'broker_secret'));
        $jwt_cookie = new Cookie($config->get('sso', 'cookie_name'));
        if (!Cookie::exists($config->get('sso', 'cookie_name'))) {
            if (empty($noauth)) {
                $this->user = $broker->login();
                $jwt_cookie->setValue(serialize($this->user));
                if (!$jwt_cookie->save()) {
                    captureMessage("JWT cookie save failed for user {$this->user->user_login}");
                }
                $this->logged = true;
            }
        } else {
            $this->user = unserialize(Cookie::get($config->get('sso', 'cookie_name')));
            $this->logged = true;
        }

        $this->db = $db;
        if (!Session::has('user_id')) {
            if ($this->logged or empty($noauth)) {
                if (!empty($renew = $broker->needsRefresh($this->user))) {
                    $this->user = $renew;
                    Session::set($config->get('sso', 'cookie_name'), serialize($this->user));
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
            }
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
        if ($update) {
            return new Result();
        } else {
            return new Result(null, $update->errorCode(), $update->errorInfo());
        }
    }

    public function adsPurchased()
    {
        return $this->db->get('users', 'ads_purchased [Bool]', ['id' => $this->id]);
    }
}