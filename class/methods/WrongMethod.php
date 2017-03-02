<?php
/**
 * @api-call wrongMethod
 *
 * Mandatory class that used for processing unknown api calls.
 * Throws BadMethodCallException with text 'Unknown method' if input
 * data does not contains api method or  'Wrong method METHOD_NAME'
 * if desired api call method can not be found.
 *
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
     * @throws \BadMethodCallException
     */
    public function __invoke() {
        throw new BadMethodCallException($this->text);
    }
}