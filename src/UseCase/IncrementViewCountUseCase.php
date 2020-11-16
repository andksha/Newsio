<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Model\Event;
use Newsio\Model\EventView;
use Newsio\Query\EventViewQuery;

final class IncrementViewCountUseCase
{
    public function incrementViewCount(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier): bool
    {
        if (!EventViewQuery::query()
            ->findUserEventViews($eventId->getValue(), $userIdentifier->getValue())
            ->lastNViews(100)
            ->orderByDesc('id')
            ->first()
        ) {
            EventView::query()->insert([
                'event_id' => $eventId->getValue(),
                'user_identifier' => $userIdentifier->getValue()
            ]);

            return (bool) Event::query()->where('id', $eventId->getValue())->increment('view_count');
        }

        return false;
    }
}