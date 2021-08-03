<?php

namespace Newsio\Factory;

use Newsio\Lib\Validation\LaravelValidator;
use Newsio\Lib\Validation\Validator;

final class ValidatorFactory
{
    public function make(): Validator
    {
        return new LaravelValidator();
    }
}