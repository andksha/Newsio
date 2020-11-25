<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\LoginUseCase;

class LoginController extends Controller
{
    public function login(Request $request, LoginUseCase $loginUseCase)
    {
        try {
           $success = $loginUseCase->login(new EmailBoundary($request->email), new PasswordBoundary($request->password));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return response()->json([
            'success' => $success
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->back();
    }
}