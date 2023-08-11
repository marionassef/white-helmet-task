<?php

namespace App\Exceptions;

use Exception;

class CustomValidationException extends Exception
{
    private $error;

    public function __construct($message,
                                $code = 0,
                                Exception $previous = null,
                                $error = [])
    {
        parent::__construct($message, $code, $previous);

        $this->error = $error;
    }

    public function getError() { return $this->error; }
}
