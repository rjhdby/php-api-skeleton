<?php

namespace errors;

class ApiException extends \RuntimeException
{
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message = 'Runtime Exception', $code = 2) {
        parent::__construct($message, $code);
    }
}