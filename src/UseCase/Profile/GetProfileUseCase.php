<?php

namespace Newsio\UseCase\Profile;

use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\EventQuery;

final class GetProfileUseCase
{
    public function getProfile(GetEventsBoundary $boundary)
    {
        return $boundary->getSaved() === 'saved'
            ? $this->getSavedEvents($boundary)
            : $this->getMyEvents($boundary);
    }

    private function getMyEvents(GetEventsBoundary $boundary)
    {
        return EventQuery::query()
            ->user($boundary->getUserId())
            ->frequentFields($boundary)
            ->withUserSaved($boundary->getUserId())
            ->orderByDesc('updated_at')
            ->paginate(15);
    }

    private function getSavedEvents(GetEventsBoundary $boundary)
    {
        return EventQuery::query()
            ->frequentFields($boundary)
            ->whereUserSaved($boundary->getUserId())
            ->withUserSaved($boundary->getUserId())
            ->orderByDesc('updated_at')
            ->paginate(15);
    }
}