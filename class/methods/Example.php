<?php
/** @api-call example */
namespace methods;

use core\Config;
use core\MethodInterface;

class Example implements MethodInterface
{
    private $data;

    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function __invoke() {
        return [Config::get('db_type')];
    }
}