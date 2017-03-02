<?php
/**
 * @api-call wrongMethod
 */
namespace methods;

use BadMethodCallException;
use core\MethodInterface;

class WrongMethod implements MethodInterface
{
    private $text = '';

    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct($data = null) {
        $this->text = isset($data[ METHOD ]) ? 'Wrong method ' . $data[ METHOD ] : 'Unknown method';
    }

    /**
     * @return array
     * @throws \BadMethodCallException
     */
    public function __invoke() {
        throw new BadMethodCallException($this->text);
    }
}