<?php
/** @api-call example */
namespace methods\example;

use core\Config;
use core\MethodInterface;

class Example implements MethodInterface
{
    private $data;

    /**
     * $_POST or $_GET array will be passed as $data argument
     * depends of GET and DEBUG constants set in environment.php
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

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
}