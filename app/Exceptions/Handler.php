<?php

namespace App\Exceptions;

use App\Http\API\APIResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return parent::render($request, $exception);
        }

        if (
            $exception instanceof UnauthorizedException
            || $exception instanceof AuthorizationException
            || $exception instanceof AuthenticationException
        ) {
            return APIResponse::error($exception->getMessage(), [], Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof Exception) {
            // @TODO: show 500 error
            return $request->expectsJson()
                ? response()->json([
                    'error_message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ])
                : dd($exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
        }

        return parent::render($request, $exception);
    }
}
