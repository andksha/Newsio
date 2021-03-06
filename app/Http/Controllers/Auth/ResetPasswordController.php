<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\ForgotPasswordUseCase;
use Newsio\UseCase\Auth\ResetPasswordUseCase;

class ResetPasswordController extends Controller
{
    public function sendResetPasswordEmail(Request $request, ForgotPasswordUseCase $forgotPasswordUseCase)
    {
        try {
            $success = $forgotPasswordUseCase->sendResetPasswordEmail(new EmailBoundary($request->email));
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

    public function getPasswordResetForm(Request $request)
    {
        return view('reset')->with(['token' => $request->token ?? '']);
    }

    public function resetPassword(Request $request, ResetPasswordUseCase $resetPasswordUseCase)
    {
        try {
            $resetPasswordUseCase->resetPassword(
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation),
                new StringBoundary($request->token)
            );
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->route('events');
    }
}