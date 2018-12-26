<?php

namespace core;

use errors\ApiException;
use errors\ParameterException;

abstract class Method implements \ArrayAccess
{
    protected $get;
    protected $post;
    protected $files;
    protected $body;

    /**
     * Method constructor.
     * @param array $get
     * @param array $post
     * @param array $files
     * @param string $body
     */
    public function __construct($get, $post, $files, $body) {
        $this->get   = $get;
        $this->post  = $post;
        $this->files = $files;
        $this->body  = $body;
    }

    /**
     * @return array
     * @throws \Exception
     */
    abstract public function __invoke();

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {
        return $this->has($offset);
    }

    /**
     * @param mixed ...$keys
     * @return bool
     */
    protected function has(...$keys) {
        foreach (\func_get_args() as $key) {
            if (!isset($this->get[ $key ])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

    protected function get($key) {
        return $this->has($key) ? $this->get[ $key ] : null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {
        throw new ApiException('Cannot reassign input parameters');
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        throw new ApiException('Cannot unset input parameters');
    }

    /**
     * @param $keys
     * @throws ParameterException
     */
    protected function checkParams(...$keys) {
        foreach (func_get_args() as $key) {
            if ($this->missing($key)) {
                throw new ParameterException($key);
            }
        }
    }

    /**
     * @param ...$keys
     * @return bool
     */
    protected function missing($keys) {
        foreach (\func_get_args() as $key) {
            if (!isset($this[ $key ])) {
                return true;
            }
        }

        return false;
    }
}