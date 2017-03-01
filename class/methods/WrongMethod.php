<?php
/**
 * @api-call wrongMethod
 */
namespace methods;

use BadMethodCallException;
use core\MethodInterface;
use core\Report;

class WrongMethod implements MethodInterface
{
    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct($data = null) {
    }

    /**
     * @return array
     * @throws \BadMethodCallException
     */
    public function __invoke() {
        Report::report('Wrong method', BadMethodCallException::class);

        return [];
    }
}