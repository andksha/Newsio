<?php

namespace Newsio\Contract;

use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;

interface EventCacheRepository
{
    public function getEvents(GetEventsBoundary $boundary): Collection;

    public function setEvents(Collection $events, GetEventsBoundary $boundary);
}