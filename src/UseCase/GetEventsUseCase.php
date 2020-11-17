<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Query\EventQuery;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $boundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        // @TODO: caching

        return EventQuery::query()
            ->frequentFields($boundary)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->withUserSaved($boundary->getUserId())
            ->paginate(15);
    }
}