<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class AddHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /** it can be a
         * @see \Symfony\Component\HttpFoundation\StreamedResponse
         */
        if (!method_exists($response, 'header')) {
            return $response;
        }
        /** @var Response */
        return $response->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}