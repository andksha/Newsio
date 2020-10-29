<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;
use Throwable;

class BoundaryException extends Exception implements ApplicationException
{
    private $errors;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->errors = $code === 0 ? [] : $code;
        $code = 0;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorData()
    {
        return $this->errors;
    }
}
