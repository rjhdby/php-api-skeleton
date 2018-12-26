<?php

use core\Config;

/** @noinspection LongInheritanceChainInspection */

final class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGetConfigValue() {
        $this->assertEquals(
            'mysql',
            Config::get('db_type')
        );
    }

    public function testGetConfigArray() {
        $this->assertEquals(
            ['one', 'two', 'three'],
            Config::getArray('array')
        );
    }

    public function testGetConfigDefaultValue() {
        $this->assertEquals(
            'mysql',
            Config::get('nothing', 'mysql')
        );
    }

    public function testExceptionOnWrongKey() {
        $this->expectException(InvalidArgumentException::class);
        Config::get('nothing');
    }

    public function testExceptionOnAbsentKey() {
        $this->expectException(InvalidArgumentException::class);
        Config::get('');
    }

    public function testParseCustomConfig() {
        $this->assertEquals(
            [
                'db_type' => 'mysql',
                'db_host' => 'localhost',
                'db_user' => 'user',
                'db_pass' => 'pass',
                'db_name' => 'db',
                'array'   => 'one, two, three'
            ],
            Config::parseCustomConfig(PROPERTIES)
        );
    }

    public function testParseIniFile() {
        $reflect = new ReflectionMethod(Config::class, 'parseIniFile');
        $reflect->setAccessible(true);
        $this->assertEquals(
            [
                'db_type' => 'mysql',
                'db_host' => 'localhost',
                'db_user' => 'user',
                'db_pass' => 'pass',
                'db_name' => 'db',
                'array'   => 'one, two, three'
            ],
            $reflect->invoke(null, PROPERTIES)
        );
    }
}
