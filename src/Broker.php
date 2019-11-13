<?php
/**
 * Copyright (c) 2019.  Maicol07 - Tutti i diritti riservati - All rights reserved
 */

namespace src;

use Curl\Curl;
use Exception;
use Lindelius\JWT\Exception\InvalidJwtException;
use Lindelius\JWT\StandardJWT;
use Sentry;

/**
 * Class Broker
 */
class Broker
{
    private $broker_id;
    private $server_url;
    private $secret;
    /** @var string */
    private $server_login_url;

    /**
     * Broker constructor.
     * @param $id
     * @param $server_url
     * @param $secret
     * @param $server_login_url
     */
    public function __construct($id, $server_url, $secret, $server_login_url = null)
    {
        $this->broker_id = $id;
        $this->server_url = $server_url;
        $url_info = parse_url($server_url);
        $this->server_login_url = $server_login_url ?: ($url_info['scheme'] . '://' . $url_info['host']);
        $this->secret = $secret;
    }

    /**
     * Sends a Curl Request
     *
     * @param $command
     * @param array $data
     * @return bool|string|null
     * @throws Exception
     */
    public function request($command, $data = [])
    {
        $data = array_merge([
            'broker_id' => $this->broker_id,
            'secret' => $this->secret,
            'command' => $command
        ], $data);

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $str_cookie = "";
        if (!empty($_SESSION['auth_cookie'])) {
            $str_cookie .= 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . ';';
            session_write_close();
        }
        if (!empty($_COOKIE['auth_cookie'])) {
            $str_cookie .= 'auth_cookie=' . $_COOKIE['auth_cookie'] . ';';
        }

        $curl = new Curl();
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $curl->setUserAgent($useragent);
        $curl->setCookieString($str_cookie);
        if (!PRODUCTION) {
            $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        }
        $curl->post($this->server_url, $data);

        if ($curl->error) {
            throw new Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n");
        }

        try {
            $result = StandardJWT::decode($curl->response);
        } catch (InvalidJwtException $e) {
            $result = $curl->response;
        }
        $curl->close();
        return $result;
    }

    public function login()
    {
        $jwt = get("code");
        if (empty($jwt)) {
            header("Location: " . $this->server_url . "?client_id=" . $this->broker_id . "&secret=" . $this->secret . "&redirect_url=" . BASEURL);
            exit();
        }
        try {
            $user = StandardJWT::decode($jwt);
        } catch (InvalidJwtException $e) {
            Sentry\captureException($e);
            die($e);
        }
        return $user->user_info;
    }
}