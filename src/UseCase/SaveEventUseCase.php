<?php

namespace Newsio\UseCase;

use App\Model\User;
use Newsio\Boundary\IdBoundary;
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
     */
    public function save(IdBoundary $eventId, IdBoundary $userId)
    {
        if (!$event = Event::query()->find($eventId->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        if (!$user = User::query()->find($userId->getValue())) {
            throw new ModelNotFoundException('User');
        }

        $userEvent = UserEvent::query()->create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);

        return $userEvent;
    }
}