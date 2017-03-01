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

    public function testCustomParse() {
        $this->assertEquals(
            ['example'     => 'methods\Example',
             'wrongMethod' => 'methods\WrongMethod'],
            Config::parseCustomConfig(METHODS)
        );
    }

    public function testGetConfigValueHard() {
        $method = new ReflectionMethod(
            new Config(), 'parseIniFile'
        );
        $method->setAccessible(true);
        $method->invoke(Config::class, PROPERTIES);
        $this->assertEquals(
            'localhost',
            Config::get('db_host')
        );
    }
}
