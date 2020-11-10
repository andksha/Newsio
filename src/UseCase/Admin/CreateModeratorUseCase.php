<?php

namespace Newsio\UseCase\Admin;

use App\Event\ModeratorCreatedEvent;
use App\Model\Moderator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Exception\AlreadyExistsException;

class CreateModeratorUseCase
{
    /**
     * @param EmailBoundary $email
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws AlreadyExistsException
     */
    public function createModerator(EmailBoundary $email): Moderator
    {
        if ($moderator = Moderator::query()->where('email', $email->getValue())->first()) {
            throw new AlreadyExistsException('Moderator already exists');
        }

        if ($moderator = Moderator::query()->create([
            'email' => $email->getValue(),
            'password' => Hash::make(Str::random(16))
        ])) {
            ModeratorCreatedEvent::dispatch($moderator->email);
        }

        return $moderator;
    }
}