<?php

namespace Newsio\Contract;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;

interface EventCacheRepository
{
    public function getEvents(GetEventsBoundary $getEventsBoundary): Collection;

    public function setEvents(Collection $events);

    public function addOrUpdateEvent(Event $event): array;

    public function removeEvent(Event $event);

    public function pushLastEvent(Closure $closure);

    public function cacheIsLoaded(): bool;

    public function getTotal(Closure $closure): int;

}