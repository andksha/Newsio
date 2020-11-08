<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\RegisterUseCase;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $uc = new RegisterUseCase();

        try {
            $user = $uc->register(
                new EmailBoundary($request->email),
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return response()->json([
            'user' => $user
        ]);
    }
}