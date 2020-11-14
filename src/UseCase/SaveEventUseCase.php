<?php

namespace Newsio\UseCase;

use App\Model\User;
use Newsio\Boundary\IdBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\UserEvent;

final class SaveEventUseCase
{
    /**
     * @param IdBoundary $eventId
     * @param IdBoundary $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     * @throws AlreadyExistsException
     */
    public function save(IdBoundary $eventId, IdBoundary $userId)
    {
        if (!$event = Event::query()->find($eventId->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        if (!$user = User::query()->find($userId->getValue())) {
            throw new ModelNotFoundException('User');
        }

        if ($userEvent = UserEvent::query()
            ->where('user_id', $userId->getValue())
            ->where('event_id', $eventId->getValue())
            ->first()
        ) {
            throw new AlreadyExistsException('Event is already saved');
        }

        $userEvent = UserEvent::query()->create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);

        return $userEvent;
    }
}