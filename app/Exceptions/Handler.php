<?php

namespace App\Exceptions;

use App\Helpers\QueryExceptionCode;
use App\Http\API\APIResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
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
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof HttpException) {
            return parent::render($request, $e);
        }

        if (
            $e instanceof UnauthorizedException
            || $e instanceof AuthorizationException
            || $e instanceof AuthenticationException
        ) {
            return APIResponse::error($e->getMessage(), [], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof QueryException) {
            if (((int)$e->getCode()) === QueryExceptionCode::INSUFFICIENT_PERMISSIONS) {
                return APIResponse::error($e->getMessage(), [], Response::HTTP_UNAUTHORIZED);
            }
            if ((int) $e->getCode() === QueryExceptionCode::UNIQUE_VIOLATION) {
                return APIResponse::error('internal server error, try again', [], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if ($e instanceof Exception) {
            // @TODO: show 500 error
            return $request->expectsJson()
                ? response()->json([
                    'error_message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ])
                : dd($e);
        }

        return parent::render($request, $e);
    }
}
