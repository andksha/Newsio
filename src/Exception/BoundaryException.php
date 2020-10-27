<?php

namespace Newsio\Exception;

use Exception;
use Newsio\Contract\ApplicationException;

class BoundaryException extends Exception implements ApplicationException
{
    public function getAdditionalData()
    {
        return;
    }
}
