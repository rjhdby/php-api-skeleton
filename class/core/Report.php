<?php

namespace core;

use Exception;

class Report
{
    /**
     * @param string $string
     * @param Exception $type
     */
    public static function reportFatal($string, $type = Exception::class) {
        self::report($string, $type);
        die('');
    }

    /**
     * @param string $string
     * @param Exception $type
     */
    public static function report($string, $type = Exception::class) {
        if (EXCEPTIONS) {
            throw new $type($string);
        }
        trigger_error($string);
    }
}