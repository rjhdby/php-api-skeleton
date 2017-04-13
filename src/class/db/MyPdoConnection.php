<?php

namespace db;

use core\Config;

class MyPdoConnection
{
    //Settings from config.php
    const TYPE    = 'db_type';
    const HOST    = 'localhost';
    const USER    = 'user';
    const PASS    = 'db_pass';
    const DB      = 'db_name';
    const CHARSET = 'db_charset';

    /** @var \PDO $connection */
    private static $connection;

    protected function __construct () { }

    /**
     * @return \PDO
     */
    public static function getInstance () {
        if (null === self::$connection) {
            self::connect();
        }

        return self::$connection;
    }

    private static function connect () {
        try {
            self::$connection = new \PDO(self::getConnectionString(), Config::get(self::USER), Config::get(self::PASS));
            self::setAttributes();
        } catch (\PDOException $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private static function getConnectionString () {
        return Config::get(self::TYPE) . ':' .
               'host=' . Config::get(self::HOST) . ';' .
               'dbname=' . Config::get(self::DB) . ';' .
               'charset=' . Config::get(self::CHARSET, 'utf8');
    }

    private static function setAttributes () {
        self::$connection->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER);
//        self::$db->query ("SET NAMES UTF8");
    }
}