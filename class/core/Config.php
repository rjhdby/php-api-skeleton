<?php
namespace core;

use InvalidArgumentException;

class Config
{
    /** @var  array $settings */
    private static $settings;

    /**
     * @param string $name
     * @param null $default
     * @return string
     */
    public static function get($name, $default = null) {
        if (empty($name)) {
            Report::report('Nothing to get', InvalidArgumentException::class);
        }
        if (self::$settings === null) {
            self::$settings = self::loadSettings(PROPERTIES);
        }
        if (isset(self::$settings[ $name ])) {
            return (string)self::$settings[ $name ];
        } elseif ($default !== null) {
            return $default;
        }
        Report::report("Setting $name not found", InvalidArgumentException::class);

        return null;
    }

    private static function loadSettings($fileName) {
        return function_exists('parse_ini_file')
            ? parse_ini_file($fileName)
            : self::parseIniFile($fileName);
    }

    private static function parseIniFile($fileName) {
        $settings = [];
        $content  = preg_grep("/^[\w .]+=.*/", explode("\n", file_get_contents($fileName)));
        foreach ($content as $row) {
            $row              = strstr($row . ';', ';', true);
            $key              = trim(strstr($row, '=', true), " \n\r");
            $settings[ $key ] = trim(strstr($row, '=', false), " \"=\n\r");
        }

        return $settings;
    }

    public static function parseCustomConfig($fileName) {
        return self::loadSettings($fileName);
    }
}