<?php

namespace Newsio\UseCase\Auth;

use App\Mail\Auth\ResetPasswordMail;
use App\Model\PasswordReset;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Exception\ModelNotFoundException;

class ForgotPasswordUseCase
{
    /**
     * @param EmailBoundary $email
     * @return bool
     * @throws ModelNotFoundException
     */
    public function sendResetPasswordEmail(EmailBoundary $email)
    {
        if (!$user = User::query()->where('email', $email->getValue())->first()) {
            throw new ModelNotFoundException('User');
        }

        $passwordReset = PasswordReset::query()->create([
            'email' => $user->email,
            'token' => Str::random()
        ]);

        Mail::to($user->email)->queue(new ResetPasswordMail($passwordReset));

        return true;
    }
}