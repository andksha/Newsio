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
            ->user($boundary->getUserId())
            ->category($boundary->getCategory())
            ->search($boundary->getSearch())
            ->tag($boundary->getTag())
            ->removed($boundary->getRemoved())
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserSavedId())
            ->paginate(15);
    }
}