<?php

namespace App\GraphQL\Middleware;

use App\GraphQL\GraphQLRequest;
use Closure;

final class StartMiddleware extends BaseMiddleware implements Middleware
{
    public function resolve(GraphQLRequest $request, Closure $nextClosure)
    {
        return $this->nextMiddleware($request, $nextClosure);
    }
}