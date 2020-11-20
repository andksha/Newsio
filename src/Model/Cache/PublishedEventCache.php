<?php

namespace Newsio\Model\Cache;

use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;

final class PublishedEventCache implements EventCacheRepository
{
    private EventCache $eventCache;

    public function __construct()
    {
        $this->eventCache = new EventCache('event', 'events', 60*60);
    }

    public function getEvents(GetEventsBoundary $boundary): Collection
    {
        return $this->eventCache->getEvents($boundary);
    }

    public function setEvents(Collection $events, GetEventsBoundary $boundary)
    {
        return $this->eventCache->setEvents($events, $boundary);
    }
}