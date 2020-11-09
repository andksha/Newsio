<?php

namespace Newsio\UseCase\Auth;

use App\Event\RegisteredEvent;
use App\Model\User;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Exception\ModelNotFoundException;

class ResendConfirmationEmailUseCase
{
    /**
     * @param EmailBoundary $email
     * @throws ModelNotFoundException
     */
    public function resend(EmailBoundary $email)
    {
        if (!$user = User::query()->where('email', $email->getValue())->first()) {
            throw new ModelNotFoundException('User');
        }

        RegisteredEvent::dispatch($email->getValue());
    }
}