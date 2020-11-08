<?php

namespace Newsio\UseCase\Auth;

use App\Event\RegisteredEvent;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Exception\InvalidOperationException;

class RegisterUseCase
{
    /**
     * @param EmailBoundary $email
     * @param PasswordBoundary $password
     * @param PasswordBoundary $passwordRepeat
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws AlreadyExistsException
     * @throws InvalidOperationException
     */
    public function register(EmailBoundary $email, PasswordBoundary $password, PasswordBoundary $passwordRepeat)
    {
        if ($user = User::query()->where('email', $email->getValue())->first()) {
            throw new AlreadyExistsException('User');
        }

        if ($password->getValue() !== $passwordRepeat->getValue()) {
            throw new InvalidOperationException('Passwords do not match');
        }

        $user = User::query()->create([
            'email' => $email->getValue(),
            'password' => Hash::make($password->getValue())
        ]);

        RegisteredEvent::dispatch($user->email);

        return $user;
    }
}