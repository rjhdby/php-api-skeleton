<?php

namespace errors;

class ExternalException extends ApiException
{

    /**
     * ExternalException constructor.
     * @param string $message
     */
    public function __construct($message = 'External error') {
        parent::__construct($message);
    }
}