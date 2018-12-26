<?php

namespace db;

use core\Config;
use errors\ExternalException;
use PDO;
use PDOStatement;

abstract class Db
{
    protected static $prefix;

    /** @var PDO $connection */
    protected static $connections = array();

    /**
     * @return PDO
     */
    public static function getConnection() {
        if (!isset(static::$connections[ static::$prefix ])) {
            static::$connections[ static::$prefix ] = new PDO(
                static::connectionString(),
                static::get('user'),
                static::get('pass')
            );
            static::setUp();
        }

        return static::$connections[ static::$prefix ];
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool|PDOStatement
     */
    public static function query($sql, array $params = array()) {
        if (empty($params)) {
            return static::getConnection()->query($sql);
        }
        $stmt = static::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * @param $sql
     * @param array $params
     * @param int $fetch
     * @return array
     */
    public static function queryAll($sql, array $params = array(), $fetch = PDO::FETCH_ASSOC) {
        return static::query($sql, $params)->fetchAll($fetch);
    }

    /**
     * @param $sql
     * @param array $params
     * @param int $fetch
     * @return array
     */
    public static function queryRow($sql, array $params = array(), $fetch = PDO::FETCH_ASSOC) {
        $result = static::query($sql, $params)->fetch($fetch);

        return $result === false ? [] : $result;
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public static function queryColumn($sql, array $params = array()) {
        return static::query($sql, $params)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * @param $sql
     * @param array $params
     * @param bool $default
     * @return mixed
     */
    public static function queryValue($sql, array $params = array(), $default = false) {
        if (empty($params)) return static::getConnection()->query($sql)->fetchColumn(0);
        $stmt = static::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchColumn(0);

        return $result === false ? $default : $result;
    }

    /**
     * @param $sql
     * @return bool|PDOStatement
     */
    public static function prepare($sql) {
        return static::getConnection()->prepare($sql);
    }

    /**
     * @param $sql
     * @param array $params
     */
    public static function execute($sql, array $params = array()) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    private function __construct() {
    }

    /**
     * @return string
     */
    private static function connectionString() {
        switch (static::get('type')) {
            case 'odbc':
                return static::odbcConnectionString();
            case 'mysql':
                return static::mysqlConnectionString();
            case 'oracle':
                return static::oracleConnectionString();
            default:
                throw new ExternalException('Unknown database type: ' . static::get('type'));
        }
    }

    private static function setUp() {
        switch (static::get('type')) {
            case 'mysql':
                static::getConnection()->exec('set names utf8');
                break;
        }
    }

    /**
     * @return string
     */
    private static function odbcConnectionString() {
        return 'odbc:' . static::get('srv');
    }

    private static function mysqlConnectionString() {
        return 'mysql:host=' . static::get('srv') . ';dbname=' . static::get('db') . ';charset=utf8';
    }

    private static function oracleConnectionString() {
        return 'oci:dbname=//' . static::get('srv') . '/' . static::get('db');
    }

    private static function get($key) {
        return Config::get(static::$prefix . '.' . $key);
    }
}