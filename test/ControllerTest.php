<?php

use core\Controller;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function testExampleCall() {
        $controller = new Controller(['m' => 'example']);
        $this->assertEquals(
            ['r' => ['mysql'], 'e' => []],
            $controller->run()
        );
    }

    public function testWrongCall() {
        $controller = new Controller(['m' => 'blablabla']);
        $this->assertEquals(
            ['r' => [], 'e' => ['code' => 0, 'text' => 'Wrong method']],
            $controller->run()
        );
    }
}