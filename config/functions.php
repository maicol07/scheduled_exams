<?php
if (!function_exists("get")) {
    /**
     * Returns a validated GET request parameter
     *
     * @param string $name
     * @return string|bool
     */
    function get($name)
    {
        return App\Utils::get($name);
    }
}

if (!function_exists("post")) {
    /**
     * Returns a validated POST request parameter
     *
     * @param string $name
     * @return string|bool
     */
    function post($name)
    {
        return App\Utils::post($name);
    }
}