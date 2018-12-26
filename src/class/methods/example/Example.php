<?php
namespace methods\example;

use core\Config;
use core\Method;

class Example extends Method
{
    /**
     * Put api call processing code here.
     * MUST return an array or throw an Exception
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function __invoke() {
        return [Config::get('db_type')];
    }

    /**
     * @param $param
     * @return mixed
     */
    public function proxyGetParam($param) {
        return $this[ $param ];
    }

    /**
     * @param $param
     * @return bool
     */
    public function proxyHas($param) {
        return $this->has($param);
    }

    /**
     * @param $param
     * @return bool
     */
    public function proxyMissing($param) {
        return $this->missing(...func_get_args());
    }

    /**
     * @param array|string $keys
     * @throws \errors\ParameterException
     */
    public function proxyCheckParams(...$keys) {
        $this->checkParams(...func_get_args());
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function proxyOffsetSet($key, $value) {
        $this[ $key ] = $value;
    }

    /**
     * @param string $key
     */
    public function proxyOffsetUnSet($key) {
        unset($this[ $key ]);
    }
}