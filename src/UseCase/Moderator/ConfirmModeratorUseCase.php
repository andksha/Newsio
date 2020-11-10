<?php

namespace Newsio\UseCase\Moderator;

use App\Model\Moderator;
use App\Model\ModeratorConfirmation;
use Carbon\Carbon;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;

class ConfirmModeratorUseCase
{
    /**
     * @param PasswordBoundary $password
     * @param PasswordBoundary $passwordConfirmation
     * @param StringBoundary $token
     * @return bool
     * @throws InvalidOperationException
     * @throws ModelNotFoundException
     */
    public function confirm(PasswordBoundary $password, PasswordBoundary $passwordConfirmation, StringBoundary $token)
    {
        if (!$moderatorConfirmation = ModeratorConfirmation::query()->where('token', $token->getValue())
            ->where('created_at', '>', Carbon::now()->subDay())->first()
        ) {
            throw new ModelNotFoundException('Password reset');
        }

        if ($password->getValue() !== $passwordConfirmation->getValue()) {
            throw new InvalidOperationException('Passwords do not match');
        }

        if (!$moderator = Moderator::query()->where('email', $moderatorConfirmation->email)->first()) {
            throw new ModelNotFoundException('User');
        }

        $moderator->changePassword($password->getValue())->verify()->save();
        $moderatorConfirmation->delete();

        return true;
    }
}