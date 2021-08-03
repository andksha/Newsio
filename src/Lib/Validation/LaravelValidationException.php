<?php

namespace Newsio\Lib\Validation;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

final class LaravelValidationException extends Exception implements ValidationException, RendersErrorsExtensions, \JsonSerializable
{
    private array $errors;

    public function __construct(\Illuminate\Validation\ValidationException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode());
        $this->errors = $e->errors();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'validation';
    }

    public function extensionsContent(): array
    {
        return $this->errors();
    }

    public function jsonSerialize(): array
    {
        return $this->errors();
    }

}