<?php

namespace Newsio\Boundary\UseCase;

use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;

final class RegisterBoundary extends BaseUseCaseBoundary
{
    protected array $boundaries = [
        'email' => EmailBoundary::class,
        'password' => PasswordBoundary::class,
        'password_confirmation' => PasswordBoundary::class,
    ];

    public function email(): string
    {
        return $this->getString('email');
    }

    public function password(): string
    {
        return $this->getString('password');
    }

    public function passwordConfirmation(): string
    {
        return $this->getString('password_confirmation');
    }
}