<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\EventQuery;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $boundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        return EventQuery::query()
            ->frequentFields($boundary)
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserId())
            ->paginate(15);
    }
}