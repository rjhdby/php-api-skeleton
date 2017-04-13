<?php
namespace core;

use InvalidArgumentException;

/**
 * Class Config
 * @package core
 *
 * Providing methods for parse INI-files and retrieve his values
 */
class Config
{
    /** @var  array $settings */
    private static $settings;

    /**
     * Return value by key from config/properties.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function get($name, $default = null) {
        if (empty($name)) {
            throw new InvalidArgumentException('Nothing to get');
        }
        if (self::$settings === null) {
            self::$settings = self::loadSettings(PROPERTIES);
        }
        if (isset(self::$settings[ $name ])) {
            return (string)self::$settings[ $name ];
        } elseif ($default !== null) {
            return $default;
        }
        throw new InvalidArgumentException("Setting $name not found");
    }

    private static function loadSettings($fileName) {
        return function_exists('parse_ini_file')
            ? parse_ini_file($fileName)
            : self::parseIniFile($fileName);
    }

    /**
     * Custom INI-file parser for cases when function "parse_ini_file" is disabled
     *
     * @param $fileName
     * @return array
     */
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

    /**
     * Parse user defined INI-file and return an associative
     * key=>value array with settings
     *
     * @param $fileName
     * @return array
     */
    public static function parseCustomConfig($fileName) {
        return self::loadSettings($fileName);
    }
}