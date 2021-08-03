<?php

namespace App\GraphQL\Middleware;

use App\GraphQL\GraphQLRequest;
use Closure;
use Illuminate\Support\Facades\Log;

final class AddHeadersMiddleware extends BaseMiddleware implements Middleware
{
    public function resolve(GraphQLRequest $request, Closure $nextClosure)
    {
        return $this->nextMiddleware($request, function () use ($request, $nextClosure) {
            Log::info('testqfwefqwef');
            $request->setArgs(array_merge($request->getArgs(), ['test' => 'test']));

            return $nextClosure($request);
        });
    }

}