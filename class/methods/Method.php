<?php
namespace methods;

interface Method
{
    /**
     * Method constructor.
     * @param array $data
     */
    public function __construct($data);

    /**
     * @return array
     */
    public function __invoke();
}