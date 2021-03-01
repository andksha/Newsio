<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;
use Newsio\Repository\BaseEventRepository;
use Newsio\Repository\EventRepositoryFactory;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $getEventsBoundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $getEventsBoundary): LengthAwarePaginator
    {
        if (
            !$this->searchParametersPresent($getEventsBoundary)
            && $getEventsBoundary->getCurrentPage() <= BaseEventRepository::MAX_CACHED_PAGES
        ) {
            return $this->loadFromCache($getEventsBoundary, new EventRepositoryFactory());
        }

        return EventQuery::query()
            ->frequentFields($getEventsBoundary)
            ->defaultOrder()
            ->withUserSaved($getEventsBoundary->getUserId())
            ->with(Event::DEFAULT_RELATIONS)
            ->paginate(15);
    }

    private function searchParametersPresent(GetEventsBoundary $getEventsBoundary): bool
    {
        return $getEventsBoundary->getSearch()
            || $getEventsBoundary->getTag()
            || $getEventsBoundary->getCategory();
    }

    public function loadFromCache(GetEventsBoundary $getEventsBoundary, EventRepositoryFactory $factory): LengthAwarePaginator
    {
        $eventRepository = $factory->makeEventRepository($getEventsBoundary);

        return $eventRepository->getEvents($getEventsBoundary);
    }
}