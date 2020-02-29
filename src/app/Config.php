<?php

namespace App;

use Matomo\Ini\IniReader;

class Config
{
    /**
     * @var array|int|string|null
     */
    private $config;

    public function __construct($file)
    {
        $ini = new IniReader();
        $this->config = $ini->readFile($file);
    }

    /**
     * Get a setting value
     *
     * @param $section
     * @param $setting
     * @return mixed|string|null
     */
    public function get($section, $setting)
    {
        if (array_key_exists($section, $this->config) and array_key_exists($setting, $this->config[$section])) {
            return $this->config[$section][$setting];
        }
        return null;
    }
}