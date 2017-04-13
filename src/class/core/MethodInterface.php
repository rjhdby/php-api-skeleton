<?php
namespace core;
/**
 * Interface MethodInterface
 * @package core
 *
 * Interface for 'methods' classes
 */
interface MethodInterface
{
    /**
     * @param array $data
     */
    public function __construct($data);

    /**
     * @return array
     * @throws \Exception
     */
    public function __invoke();
}