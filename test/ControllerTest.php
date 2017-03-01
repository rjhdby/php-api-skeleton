<?php

use core\Controller;

/** @noinspection LongInheritanceChainInspection */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    public function testExampleCall() {
        $controller = new Controller(['m' => 'example']);
        $this->assertEquals(
            ['r' => ['mysql'], 'e' => []],
            $controller->run()
        );
    }

    public function testWrongCall() {
        $controller = new Controller(['m' => 'nothing']);
        $this->assertEquals(
            ['r' => [], 'e' => ['code' => 0, 'text' => 'Wrong method']],
            $controller->run()
        );
    }
}