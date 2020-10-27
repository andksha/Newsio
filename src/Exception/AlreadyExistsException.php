<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;
use Throwable;

class AlreadyExistsException extends Exception implements ApplicationException
{
    private $existingModel;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->existingModel = $code;
        $code = 0;
        parent::__construct($message, $code, $previous);
    }

    public function getAdditionalData()
    {
        return $this->existingModel;
    }
}
