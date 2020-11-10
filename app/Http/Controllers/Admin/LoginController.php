<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\LoginUseCase;

class LoginController
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request, LoginUseCase $uc)
    {
        try {
            $uc->login(new EmailBoundary($request->email), new PasswordBoundary($request->password), 'admin');
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->route('websites', 'pending');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('events');
    }
}