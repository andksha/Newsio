<?php

namespace App\Http\API\Controllers\Admin;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\LoginUseCase;

class LoginController extends Controller
{
    public function login(Request $request, LoginUseCase $uc): JsonResponse
    {
        try {
            $token = $uc->login(new EmailBoundary($request->email), new PasswordBoundary($request->password), 'api_admin');
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok($this->token($token), Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api_admin')->logout();

        return APIResponse::ok([], Response::HTTP_OK);
    }
}