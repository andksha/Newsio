<?php

namespace Newsio\UseCase\Auth;

use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Exception\InvalidDataException;

class LoginUseCase
{
    /**
     * @param EmailBoundary $email
     * @param PasswordBoundary $password
     * @param string $guard
     * @return mixed
     * @throws InvalidDataException
     */
    public function login(EmailBoundary $email, PasswordBoundary $password, string $guard = 'web')
    {
        if (!Auth::guard($guard)->attempt([
            'email' => $email->getValue(),
            'password' => $password->getValue()
        ])) {
            throw new InvalidDataException('Invalid credentials');
        }

        return true;
    }
}