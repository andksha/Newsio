<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Category;
use Newsio\UseCase\Auth\LoginUseCase;
use Newsio\UseCase\Moderator\ConfirmModeratorUseCase;

class AuthController extends Controller
{
    public function confirmModeratorForm(Request $request)
    {
        return view('moderator.confirmation')->with([
            'token' => $request->token,
            'categories' => Category::all()
        ]);
    }

    public function confirmModerator(Request $request, ConfirmModeratorUseCase $uc)
    {
        try {
            $uc->confirm(
                new PasswordBoundary($request->password),
                new PasswordBoundary($request->password_confirmation),
                new StringBoundary($request->token)
            );
        } catch (ApplicationException $e) {
            return $this->redirectBackWithError($e);
        }

        return redirect()->route('moderator_login_form');
    }

    public function loginForm()
    {
        return view('moderator.login');
    }

    public function login(Request $request, LoginUseCase $uc)
    {
        try {
            $uc->login(new EmailBoundary($request->email), new PasswordBoundary($request->password), 'moderator');
        } catch (ApplicationException $e) {
            return $this->redirectBackWithError($e);
        }

        return redirect()->route('events');
    }

    public function logout()
    {
        Auth::guard('moderator')->logout();

        return redirect()->route('events');
    }
}