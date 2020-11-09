<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;

class InvalidDataException extends Exception implements ApplicationException
{
    public function getErrorData()
    {
        return [];
    }
}