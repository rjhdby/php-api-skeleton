<?php

use core\Controller;
use errors\ApiException;
use errors\ParameterException;
use methods\example\Example;

/** @noinspection LongInheritanceChainInspection */

final class MethodTest extends PHPUnit_Framework_TestCase
{
    public function testGetParam() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example', 'param' => 'data']);

        $this->assertEquals(
            'data',
            $method->proxyGetParam('param')
        );
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

    public function testHas() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example', 'param' => 'data']);

        $this->assertEquals(
            true,
            $method->proxyHas('param')
        );

        $this->assertEquals(
            false,
            $method->proxyHas('param1')
        );
    }

    public function testMissing() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example', 'param' => 'data']);

        $this->assertEquals(
            true,
            $method->proxyMissing('param', 'missing')
        );

        $this->assertEquals(
            false,
            $method->proxyMissing('param', METHOD)
        );
    }

    public function testCheckParams() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example', 'param1' => 'data', 'param2' => 'data']);

        try {
            $method->proxyCheckParams('param1', 'param2');
        } catch (ParameterException $e) {
            $this->fail();
        }
        $this->expectException(ParameterException::class);
        $method->proxyCheckParams('param1', 'missing');
    }

    public function testOffsetSet() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example']);
        $this->expectException(ApiException::class);
        $method->proxyOffsetSet('param', 'value');
    }

    public function testOffsetUnSet() {
        /** @var Example $method */
        $method = $this->initCall([METHOD => 'example']);
        $this->expectException(ApiException::class);
        $method->proxyOffsetUnSet('param');
    }
}
