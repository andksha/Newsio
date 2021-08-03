<?php

namespace Newsio\Lib\Validation;

use Throwable;

interface ValidationException extends Throwable
{
    public function errors(): array;
}