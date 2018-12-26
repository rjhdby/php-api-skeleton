<?php

namespace errors;

class NotFoundException extends \Exception
{

    /**
     * NotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Requested data not found') {
        parent::__construct($message);
    }
}