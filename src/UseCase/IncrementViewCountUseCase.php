<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\EventView;
use Newsio\Query\EventViewQuery;

final class IncrementViewCountUseCase
{
    /**
     * @param IdBoundary $eventId
     * @param UserIdentifierBoundary $userIdentifier
     * @return bool
     * @throws ModelNotFoundException
     */
    public function incrementViewCount(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier): bool
    {
        if (!EventViewQuery::query()
            ->findUserEventViews($eventId->getValue(), $userIdentifier->getValue())
            ->orderByDesc('id')
            ->first()
        ) {
            EventView::query()->insert([
                'event_id' => $eventId->getValue(),
                'user_identifier' => $userIdentifier->getValue()
            ]);

            if (!$event = Event::query()->find($eventId->getValue())) {
                throw new ModelNotFoundException('Event');
            }

            return $event->incrementViewCount();
        }

        return false;
    }
}