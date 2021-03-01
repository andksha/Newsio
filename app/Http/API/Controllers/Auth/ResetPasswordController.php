<?php

namespace App\Http\API\Controllers\Auth;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\ForgotPasswordUseCase;
use Newsio\UseCase\Auth\ResetPasswordUseCase;

class ResetPasswordController extends Controller
{
    public function sendResetPasswordEmail(Request $request, ForgotPasswordUseCase $forgotPasswordUseCase): JsonResponse
    {
        try {
            $forgotPasswordUseCase->sendResetPasswordEmail(new EmailBoundary($request->email));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_ACCEPTED);
    }

    public function resetPassword(Request $request, ResetPasswordUseCase $resetPasswordUseCase): JsonResponse
    {
        try {
            $resetPasswordUseCase->resetPassword(
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation),
                new StringBoundary($request->token)
            );
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_ACCEPTED);
    }
}