<?php

namespace Newsio\UseCase\Auth;

use App\Model\PasswordReset;
use App\Model\User;
use Carbon\Carbon;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;

class ResetPasswordUseCase
{
    /**
     * @param StringBoundary $password
     * @param StringBoundary $passwordConfirmation
     * @param StringBoundary $token
     * @return bool
     * @throws InvalidOperationException
     * @throws ModelNotFoundException
     */
    public function resetPassword(StringBoundary $password, StringBoundary $passwordConfirmation, StringBoundary $token)
    {
        if (!$passwordReset = PasswordReset::query()->where('token', $token->getValue())
            ->where('created_at', '>', Carbon::now()->subDay())->first()
        ) {
            throw new ModelNotFoundException('Password reset');
        }

        if ($password->getValue() !== $passwordConfirmation->getValue()) {
            throw new InvalidOperationException('Passwords do not match');
        }

        if (!$user = User::query()->where('email', $passwordReset->email)->first()) {
            throw new ModelNotFoundException('User');
        }

        $user->changePassword($password->getValue());
        $passwordReset->delete();

        return true;
    }
}