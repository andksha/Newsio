<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Category;
use Newsio\UseCase\Auth\ForgotPasswordUseCase;
use Newsio\UseCase\Auth\ResetPasswordUseCase;

class ResetPasswordController extends Controller
{
    public function sendResetPasswordEmail(Request $request)
    {
        $uc = new ForgotPasswordUseCase();

        try {
            $success = $uc->sendResetPasswordEmail(new EmailBoundary($request->email));
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
        return view('reset')->with([
            'token' => $request->token ?? '',
            'categories' => Category::all()
        ]);
    }

    public function resetPassword(Request $request)
    {
        $uc = new ResetPasswordUseCase();

        try {
            $uc->resetPassword(
                new StringBoundary($request->password),
                new StringBoundary($request->password_confirmation),
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