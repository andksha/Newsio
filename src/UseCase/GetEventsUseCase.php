<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;
use Newsio\Repository\EventRepositoryFactory;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $boundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        if (!$this->searchParametersPresent($boundary) && $boundary->getCurrentPage() <= Event::MAX_CACHED_PAGES) {
            return $this->loadFromCache($boundary, new EventRepositoryFactory());
        }

        return EventQuery::query()
            ->frequentFields($boundary)
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserId())
            ->with(['tags', 'links', 'removedLinks', 'category'])
            ->paginate(15);
    }

    private function searchParametersPresent(GetEventsBoundary $boundary): bool
    {
        return $boundary->getSearch()
            || $boundary->getTag()
            || $boundary->getCategory();
    }

    public function loadFromCache(GetEventsBoundary $boundary, EventRepositoryFactory $factory): LengthAwarePaginator
    {
        $eventRepository = $factory->makeEventRepository($boundary);

        return $eventRepository->getEvents($boundary);
    }
}