<?php

namespace App\Http\Middleware;

use App\Http\API\APIResponse;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class EmailIsVerified
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
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {
            $message = 'Email must be verified';
            return $request->expectsJson()
                ? APIResponse::error($message, [], Response::HTTP_FORBIDDEN)
                : redirect()->back()->with([
                    'error_message' => $message
                ]);
        }

        return $next($request);
    }
}