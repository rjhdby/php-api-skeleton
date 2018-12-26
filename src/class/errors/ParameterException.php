<?php

namespace errors;

class ParameterException extends \Exception
{

    /**
     * ParameterException constructor.
     * @param $parameter
     */
    public function __construct($parameter) {
        parent::__construct("Wrong parameter $parameter", 2);
    }
}