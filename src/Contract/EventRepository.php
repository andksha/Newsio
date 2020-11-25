<?php

namespace Newsio\Contract;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;

interface EventRepository
{
    public function getEvents(GetEventsBoundary $getEventsBoundary): LengthAwarePaginator;

    public function removeEvent(Event $event);

}