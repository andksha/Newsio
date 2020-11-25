<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\ConfirmEmailUseCase;
use Newsio\UseCase\Auth\RegisterUseCase;
use Newsio\UseCase\Auth\ResendConfirmationEmailUseCase;

class RegisterController extends Controller
{
    public function register(Request $request, RegisterUseCase $registerUseCase)
    {
        try {
            $user = $registerUseCase->register(
                new EmailBoundary($request->email),
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation)
            );
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

    public function confirm(Request $request, ConfirmEmailUseCase $confirmEmailUseCase)
    {
        try {
            $confirmEmailUseCase->confirm(new StringBoundary($request->token));
        } catch (ApplicationException $e) {
            return redirect()->route('events')->with(['error_message' => $e->getMessage()]);
        }

        return redirect()->route('events');
    }

    public function resendConfirmationEmail(ResendConfirmationEmailUseCase $resendConfirmationEmailUseCase)
    {
        $user = auth()->user();

        try {
            $resendConfirmationEmailUseCase->resend(new EmailBoundary($user->email));
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->back();
    }
}