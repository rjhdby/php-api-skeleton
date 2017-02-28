<?php

use core\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
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
            Config::get('blablabla', 'mysql')
        );
    }

    public function testExceptionOnWrongKey() {
        $this->expectException(InvalidArgumentException::class);
        Config::get('blablabla');
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
            Config::class, 'parseIniFile'
        );
        $method->setAccessible(true);
        $method->invoke(Config::class, PROPERTIES);
        $this->assertEquals(
            'localhost',
            Config::get('db_host')
        );
    }
}
