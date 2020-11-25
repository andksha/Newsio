<?php

namespace Newsio\UseCase\Profile;

use Newsio\Boundary\UseCase\GetProfileBoundary;
use Newsio\Query\EventQuery;

final class GetProfileUseCase
{
    public function getProfile(GetProfileBoundary $getProfileBoundary)
    {
        return $getProfileBoundary->getSaved() === 'saved'
            ? $this->getSavedEvents($getProfileBoundary)
            : $this->getMyEvents($getProfileBoundary);
    }

    private function getMyEvents(GetProfileBoundary $getProfileBoundary)
    {
        return EventQuery::query()
            ->user($getProfileBoundary->getUserId())
            ->frequentFields($getProfileBoundary->getEventsBoundary())
            ->withUserSaved($getProfileBoundary->getUserId())
            ->defaultOrder()
            ->paginate(15);
    }

    private function getSavedEvents(GetProfileBoundary $getProfileBoundary)
    {
        return EventQuery::query()
            ->frequentFields($getProfileBoundary->getEventsBoundary())
            ->whereUserSaved($getProfileBoundary->getUserId())
            ->withUserSaved($getProfileBoundary->getUserId())
            ->defaultOrder()
            ->paginate(15);
    }
}