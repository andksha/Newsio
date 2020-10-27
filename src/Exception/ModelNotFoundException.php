<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;
use Throwable;

class ModelNotFoundException extends Exception implements ApplicationException
{
    protected $message = 'not found';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message . ' ' . $this->message;
        parent::__construct($message, $code, $previous);
    }

    public function getAdditionalData()
    {
        return;
    }
}
