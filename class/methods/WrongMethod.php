<?php
/**
 * @api-call wrongMethod
 */
namespace methods;

use BadMethodCallException;
use core\MethodInterface;

class WrongMethod implements MethodInterface
{
    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct ($data = null) {
    }

    /**
     * @return array
     * @throws \BadMethodCallException
     */
    public function __invoke () {
        throw new BadMethodCallException('Wrong method');
    }
}