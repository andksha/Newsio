<?php

namespace App\GraphQL\Middleware;

use App\GraphQL\GraphQLRequest;
use Closure;

interface Middleware
{
    public function addNext(Middleware $middleware);

    public function nextMiddleware(GraphQLRequest $request, Closure $nextClosure);

    public function resolve(GraphQLRequest $request, Closure $nextClosure);
}