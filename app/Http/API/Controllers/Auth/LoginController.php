<?php

namespace App\Http\API\Controllers\Auth;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\DTO\TokenDTO;
use Newsio\UseCase\Auth\LoginUseCase;

class LoginController extends Controller
{
    public function login(Request $request, LoginUseCase $loginUseCase): JsonResponse
    {
        try {
            $token = $loginUseCase->login(new EmailBoundary($request->email), new PasswordBoundary($request->password), 'api');
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNAUTHORIZED);
        }

        return APIResponse::ok($token->jsonSerialize(), Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return APIResponse::ok([], Response::HTTP_OK);
    }

    public function refresh(): JsonResponse
    {
        $token = new TokenDTO(auth('api')->refresh(), TokenDTO::TYPE_BEARER, config('jwt.ttl'));

        return APIResponse::ok($token->jsonSerialize(), Response::HTTP_CREATED);
    }
}