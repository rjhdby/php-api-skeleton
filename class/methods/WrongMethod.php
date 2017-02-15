<?php
namespace methods;

use BadMethodCallException;
use core\Report;

class WrongMethod implements Method
{
    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct($data) {
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