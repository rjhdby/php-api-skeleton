<?php

use core\Controller;
use errors\ParameterException;
use errors\WrongMethodException;

/** @noinspection LongInheritanceChainInspection */

class ControllerTest extends PHPUnit_Framework_TestCase
{
    public function testGetMethodFromGet() {
        $method = $this->initCall([METHOD => 'example']);
        $this->assertEquals(
            ['mysql'],
            $method()
        );
    }

    public function testGetMethodFromPost() {
        $method = $this->initCall([], [METHOD => 'example']);
        $this->assertEquals(
            ['mysql'],
            $method()
        );
    }

    public function testGetMethodFromBody() {
        $method = $this->initCall([], [], [], '{"m":"example"}');
        $this->assertEquals(
            ['mysql'],
            $method()
        );
    }

    public function testWrongMethod() {
        $this->expectException(WrongMethodException::class);
        $this->initCall([METHOD => 'badMethod']);
    }

    public function testNoMethod() {
        $this->expectException(ParameterException::class);
        $this->initCall();
    }

    public function testExpiredCacheForMethodFile() {
        $file                        = file_get_contents(METHODS);
        $methods                     = json_decode($file, true);
        $methods['example']['mtime'] = 0;
        file_put_contents(METHODS, json_encode($methods));
        $this->testGetMethodFromGet();
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $files
     * @param string $body
     * @return \core\Method
     * @throws \errors\ParameterException
     */
    private function initCall(array $get = [], array $post = [], array $files = [], $body = '') {
        return Controller::getMethod($get, $post, $files, $body);
    }
}