<?php

use core\Controller;

/** @noinspection LongInheritanceChainInspection */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    public function testExampleCall () {
        $controller = new Controller(['m' => 'example']);
        $this->assertEquals(
            ['r' => ['mysql'], 'e' => []],
            $controller->run()
        );
    }

    public function testWrongCall () {
        $controller = new Controller(['m' => 'nothing']);
        $this->assertEquals(
            ['r' => [], 'e' => ['code' => 0, 'text' => 'Wrong method']],
            $controller->run()
        );
    }

    public function testUseStaticMapping () {
        $method = new ReflectionMethod(
            new Controller([]), 'mapStatic'
        );
        $method->setAccessible(true);
        $this->assertEquals([
                                'example'     => 'methods\Example',
                                'wrongMethod' => 'methods\WrongMethod'
                            ],
                            $method->invoke(new Controller([]))
        );
    }

    public function testParseTokensForDumbFile () {
        $method = new ReflectionMethod(
            new Controller([]), 'parseTokens'
        );
        $method->setAccessible(true);
        $this->assertEquals(false,
                            $method->invoke(new Controller([]), token_get_all(file_get_contents(ROOT . '/class/core/MethodInterface.php')))
        );
    }
}