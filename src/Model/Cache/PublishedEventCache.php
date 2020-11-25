<?php

namespace Newsio\Model\Cache;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Model\Event;
use Newsio\Repository\PublishedEventRepository;

final class PublishedEventCache implements EventCacheRepository
{
    private EventCache $eventCache;

    public function __construct()
    {
        $this->eventCache = new EventCache('event', 'events', 60*60, PublishedEventRepository::TO_CACHE);
    }

    public function getEvents(GetEventsBoundary $getEventsBoundary): Collection
    {
        return $this->eventCache->getEvents($getEventsBoundary);
    }

    public function setEvents(Collection $events)
    {
        return $this->eventCache->setEvents($events);
    }

    public function addOrUpdateEvent(Event $event): array
    {
        return $this->eventCache->addOrUpdateEvent($event);
    }

    public function removeEvent(Event $event)
    {
        return $this->eventCache->removeEvent($event);
    }

    public function pushLastEvent(Closure $closure)
    {
        return $this->eventCache->pushLastEvent($closure);
    }

    public function cacheIsLoaded(): bool
    {
        return $this->eventCache->cacheIsLoaded();
    }

    public function getTotal(Closure $closure): int
    {
        return $this->eventCache->getTotal($closure);
    }

}