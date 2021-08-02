<?php

namespace App\GraphQL\Error;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

final class GraphqlError extends Exception implements RendersErrorsExtensions
{
    public function extensionsContent(): array
    {
        return [];
    }

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'client';
    }

}