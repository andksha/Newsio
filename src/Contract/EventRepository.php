<?php

namespace Newsio\Contract;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;

interface EventRepository
{
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator;

    public function removeEvent(Event $event);

}