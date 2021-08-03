<?php

namespace App\GraphQL\Middleware;

use App\GraphQL\GraphQLRequest;
use Closure;

abstract class BaseMiddleware implements Middleware
{
    protected Middleware $nextMiddleware;

    public function addNext(Middleware $middleware): Middleware
    {
        $this->nextMiddleware = $middleware;

        return $this->nextMiddleware;
    }

    public function nextMiddleware(GraphQLRequest $request, Closure $nextClosure)
    {
        if (isset($this->nextMiddleware)) {
            return $this->nextMiddleware->resolve($request, $nextClosure);
        }

        return $nextClosure();
    }
}