<?php

namespace Newsio\UseCase\Auth;

use Illuminate\Support\Facades\Auth;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\DTO\TokenDTO;
use Newsio\Exception\InvalidDataException;

class LoginUseCase
{
    /**
     * @param EmailBoundary $email
     * @param PasswordBoundary $password
     * @param string $guard
     * @return TokenDTO|bool
     * @throws InvalidDataException
     */
    public function login(EmailBoundary $email, PasswordBoundary $password, string $guard = 'web')
    {
        $this->logAllUsersOut();

        if (!$token = Auth::guard($guard)->attempt([
            'email' => $email->getValue(),
            'password' => $password->getValue()
        ])) {
            throw new InvalidDataException('Invalid credentials');
        }

        if (is_bool($token)) {
            return $token;
        }

        return new TokenDTO($token, TokenDTO::TYPE_BEARER, config('jwt.ttl'));
    }

    private function logAllUsersOut()
    {
        Auth::guard('web')->logout();
        Auth::guard('moderator')->logout();
        Auth::guard('admin')->logout();
    }
}