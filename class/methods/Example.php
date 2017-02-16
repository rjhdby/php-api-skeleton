<?php
/** @api-call example */
namespace methods;

class Example implements Method
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
        return $this->data;
    }
}