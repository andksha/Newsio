<?php

namespace Newsio\Repository;

use Illuminate\Support\Facades\DB;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Model\Cache\EventViewCache;
use Newsio\Model\Event;
use Newsio\Query\EventViewQuery;

final class EventViewRepository
{
    private EventViewCache $eventViewCache;

    public function __construct(EventViewCache $eventViewCache)
    {
        $this->eventViewCache = $eventViewCache;
    }

    public function getEventView(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier)
    {
        if (!$eventView = $this->eventViewCache->getEventView($eventId, $userIdentifier)) {
            return EventViewQuery::query()
                ->findUserEventViews($eventId->getValue(), $userIdentifier->getValue())
                ->orderByDesc('id')
                ->first();
        }

        return $eventView;
    }

    public function getEvent(IdBoundary $eventId)
    {
        if (!$event = $this->eventViewCache->getEvent($eventId)) {
            return Event::query()->find($eventId->getValue());
        }

        return $event;
    }

    public function createEventView(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier)
    {
        return $this->eventViewCache->createEventView($eventId, $userIdentifier);
    }

    public function updateEvents()
    {
        $eventViewCounts = $this->eventViewCache->getEventViewCounts();

        foreach ($eventViewCounts as $key => $value) {
            DB::table('events')->where('id', $key)->update(['view_count' => DB::raw('view_count + ' . $value)]);
        }

        $this->eventViewCache->clearEventViewCount();
    }
}