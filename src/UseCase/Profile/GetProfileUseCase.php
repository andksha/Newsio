<?php

namespace Newsio\UseCase\Profile;

use Newsio\Boundary\UseCase\GetProfileBoundary;
use Newsio\Query\EventQuery;

final class GetProfileUseCase
{
    public function getProfile(GetProfileBoundary $boundary)
    {
        return $boundary->getSaved() === 'saved'
            ? $this->getSavedEvents($boundary)
            : $this->getMyEvents($boundary);
    }

    private function getMyEvents(GetProfileBoundary $boundary)
    {
        return EventQuery::query()
            ->user($boundary->getUserId())
            ->frequentFields($boundary->getEventsBoundary())
            ->withUserSaved($boundary->getUserId())
            ->orderByDesc('updated_at')
            ->paginate(15);
    }

    private function getSavedEvents(GetProfileBoundary $boundary)
    {
        return EventQuery::query()
            ->frequentFields($boundary->getEventsBoundary())
            ->whereUserSaved($boundary->getUserId())
            ->withUserSaved($boundary->getUserId())
            ->orderByDesc('updated_at')
            ->paginate(15);
    }
}