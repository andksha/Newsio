<?php

namespace Newsio\UseCase\Auth;

use App\Model\EmailConfirmation;
use App\Model\User;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;

class ConfirmEmailUseCase
{
    /**
     * @param StringBoundary $token
     * @return User
     * @throws ModelNotFoundException
     */
    public function confirm(StringBoundary $token)
    {
        if (!$emailConfirmation = EmailConfirmation::query()->where('token', $token->getValue())->first()) {
            throw new ModelNotFoundException('Email confirmation');
        }

        if (!$user = User::query()->where('email', $emailConfirmation->email)->first()) {
            throw new ModelNotFoundException('User');
        }

        $user->verify();
        $emailConfirmation->delete();

        return $user;
    }
}