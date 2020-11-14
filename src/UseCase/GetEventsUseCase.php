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
        if ($boundary->getSaved() === 'saved') {
            $userId = null;
            $userSavedId = $boundary->getUserId();
        } else {
            $userId = $boundary->getUserId();
            $userSavedId = null;
        }

        return EventQuery::query()
            ->user($userId)
            ->category($boundary->getCategory())
            ->search($boundary->getSearch())
            ->tag($boundary->getTag())
            ->removed($boundary->getRemoved())
            ->whereUserSaved($userSavedId)
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserId())
            ->paginate(15);
    }
}