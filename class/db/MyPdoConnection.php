<?php

namespace db;

use core\Config;
use core\Report;

class MyPdoConnection
{
    //Settings from config.php
    const TYPE    = 'db_type';
    const HOST    = 'localhost';
    const USER    = 'user';
    const PASS    = 'db_pass';
    const DB      = 'db_dbname';
    const CHARSET = 'db_charset';

    /** @var \PDO $db */
    private static $db;

    private function __construct() { }

    /**
     * @return \PDO
     */
    public static function getInstance() {
        if (null === self::$db) {
            self::connect();
        }

        return self::$db;
    }

    private static function connect() {
        try {
            self::$db = new \PDO(self::getConnectionString(), Config::get(self::USER), Config::get(self::PASS));
            self::setAttributes();
        } catch (\PDOException $e) {
            Report::reportFatal($e->getMessage());
        }
    }

    /**
     * @return string
     */
    private static function getConnectionString() {
        return Config::get(self::TYPE) . ':' .
               'host=' . Config::get(self::HOST) . ';' .
               'dbname=' . Config::get(self::DB) . ';' .
               'charset=' . Config::get(self::CHARSET, 'utf8');
    }

    private static function setAttributes() {
        self::$db->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER);
    }
}