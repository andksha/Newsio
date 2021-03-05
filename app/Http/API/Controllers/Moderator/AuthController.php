<?php

namespace App\Http\API\Controllers\Moderator;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\LoginUseCase;
use Newsio\UseCase\Moderator\ConfirmModeratorUseCase;

class AuthController extends Controller
{
    public function confirmModerator(Request $request, ConfirmModeratorUseCase $confirmModeratorUseCase): JsonResponse
    {
        try {
            $confirmModeratorUseCase->confirm(
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation),
                new StringBoundary($request->token)
            );
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);

        }

        return APIResponse::ok([], Response::HTTP_OK);
    }

    public function login(Request $request, LoginUseCase $loginUseCase): JsonResponse
    {
        try {
            $token = $loginUseCase->login(new EmailBoundary($request->email), new PasswordBoundary($request->password), 'api_moderator');
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok($this->token($token), Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api_moderator')->logout();

        return APIResponse::ok([], Response::HTTP_OK);
    }
}