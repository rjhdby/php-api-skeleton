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
            parse_ini_file(PROPERTIES);
        }
        if (isset(self::$settings[ $name ])) {
            return (string)self::$settings[ $name ];
        } elseif ($default !== null) {
            return $default;
        }
        Report::reportFatal("Setting $name not found", InvalidArgumentException::class);

        return null;
    }
}