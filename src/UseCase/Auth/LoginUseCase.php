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
     * @return mixed
     * @throws InvalidDataException
     */
    public function login(EmailBoundary $email, PasswordBoundary $password)
    {
        if (!Auth::attempt([
            'email' => $email->getValue(),
            'password' => $password->getValue()
        ])) {
            throw new InvalidDataException('Invalid credentials');
        }

        return true;
    }
}