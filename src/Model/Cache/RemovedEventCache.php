<?php

namespace Newsio\Model\Cache;

use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Model\Event;
use Newsio\Repository\RemovedEventRepository;

final class RemovedEventCache implements EventCacheRepository
{
    private EventCache $eventCache;

    public function __construct()
    {
        $this->eventCache = new EventCache('event.removed', 'events.removed', 60*60, RemovedEventRepository::TO_CACHE);
    }

    public function getEvents(GetEventsBoundary $boundary): Collection
    {
        return $this->eventCache->getEvents($boundary);
    }

    public function setEvents(Collection $events)
    {
        return $this->eventCache->setEvents($events);
    }

    public function addOrUpdateEvent(Event $event): array
    {
        return $this->eventCache->addOrUpdateEvent($event);
    }

    public function cacheIsLoaded(): bool
    {
        return $this->eventCache->cacheIsLoaded();
    }
}