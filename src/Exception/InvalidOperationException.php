<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;

class InvalidOperationException extends Exception implements ApplicationException
{
    public function getErrorData()
    {
        return;
    }
}