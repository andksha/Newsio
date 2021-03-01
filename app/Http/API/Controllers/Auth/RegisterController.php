<?php

namespace App\Http\API\Controllers\Auth;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\UseCase\RegisterBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\ConfirmEmailUseCase;
use Newsio\UseCase\Auth\RegisterUseCase;
use Newsio\UseCase\Auth\ResendConfirmationEmailUseCase;

class RegisterController extends Controller
{
    /**
     * Register new user by email and password
     * @param Request $request
     * @param RegisterUseCase $registerUseCase
     * @return JsonResponse
     */
    public function register(Request $request, RegisterUseCase $registerUseCase): JsonResponse
    {
        try {
            $user = $registerUseCase->register(new RegisterBoundary([
                    'email' => $request->email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                ])
            );
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([
            'user' => $user
        ], Response::HTTP_CREATED);

    }

    public function confirm(Request $request, ConfirmEmailUseCase $confirmEmailUseCase): JsonResponse
    {
        try {
            $confirmEmailUseCase->confirm(new StringBoundary($request->token));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_OK);
    }

    public function resendConfirmationEmail(ResendConfirmationEmailUseCase $resendConfirmationEmailUseCase): JsonResponse
    {
        $user = auth()->user();

        try {
            $resendConfirmationEmailUseCase->resend(new EmailBoundary($user->email ?? ''));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_ACCEPTED);
    }
}