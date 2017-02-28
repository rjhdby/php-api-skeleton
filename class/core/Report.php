<?php

namespace core;

use Exception;

class Report
{
    /**
     * @param string $string
     */
    public static function reportFatal($string) {
        trigger_error($string, E_USER_ERROR);
    }

    /**
     * @param string $string
     * @param Exception $type
     */
    public static function report($string, $type = Exception::class) {
        if (EXCEPTIONS) {
            throw new $type($string);
        }
        trigger_error($string, E_USER_WARNING);
    }
}