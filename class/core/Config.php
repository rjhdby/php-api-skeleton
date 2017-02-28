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
            Report::reportFatal('Nothing to get', InvalidArgumentException::class);
        }
        if (self::$settings === null) {
            self::loadSettings();
        }
        if (isset(self::$settings[ $name ])) {
            return (string)self::$settings[ $name ];
        } elseif ($default !== null) {
            return $default;
        }
        Report::reportFatal("Setting $name not found", InvalidArgumentException::class);

        return null;
    }

    private static function loadSettings() {
        if (function_exists('parse_ini_file')) {
            self::$settings = parse_ini_file(PROPERTIES);

            return;
        }
        $content = preg_grep("/^[\w .]+=.*/", explode(PHP_EOL, file_get_contents(PROPERTIES)));
        foreach ($content as $row) {
            $row                    = strstr($row . ';', ';', true);
            $key                    = trim(strstr($row, '=', true), " \n\r");
            $value                  = trim(strstr($row, '=', false), " \"=\n\r");
            self::$settings[ $key ] = $value;
        }
    }
}