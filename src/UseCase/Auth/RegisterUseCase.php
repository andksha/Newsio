<?php

namespace Newsio\UseCase\Auth;

use App\Event\RegisteredEvent;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Newsio\Boundary\UseCase\RegisterBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Exception\InvalidOperationException;

class RegisterUseCase
{
    /**
     * @param RegisterBoundary $boundary
     * @return User
     * @throws AlreadyExistsException
     * @throws InvalidOperationException
     */
    public function register(RegisterBoundary $boundary): User
    {
        if ($user = User::query()->where('email', $boundary->email())->first()) {
            throw new AlreadyExistsException('User already exists');
        }

        if ($boundary->password() !== $boundary->passwordConfirmation()) {
            throw new InvalidOperationException('Passwords do not match');
        }

        if ($user = User::query()->create([
            'email' => $boundary->email(),
            'password' => Hash::make($boundary->password())
        ])) {
            RegisteredEvent::dispatch($user->email);
        }

        return $user;
    }
}