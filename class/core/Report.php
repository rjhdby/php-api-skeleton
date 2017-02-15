<?php

namespace core;

use Exception;

class Report
{
    /**
     * @param string $string
     * @param Exception $type
     */
    public static function report($string, $type = Exception::class) {
        require_once __DIR__ . '/../../environment.php';
        if (EXCEPTIONS) {
            throw new $type($string);
        } else {
            trigger_error($string);
        }
    }

    /**
     * @param string $string
     * @param Exception $type
     */
    public static function reportFatal($string, $type = Exception::class) {
        self::report($string, $type);
        die('');
    }
}