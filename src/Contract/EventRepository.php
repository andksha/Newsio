<?php

namespace Newsio\Contract;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;

interface EventRepository
{
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator;
}