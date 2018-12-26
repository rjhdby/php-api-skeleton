<?php

namespace errors;

class WrongMethodException extends ApiException
{

    /**
     * WrongMethodException constructor.
     * @param string $name
     */
    public function __construct($name = '') {
        parent::__construct("Wrong method $name", 2);
    }
}