<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $boundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        if (!$this->searchParametersPresent($boundary) && $boundary->getCurrentPage() <= Event::MAX_CACHED_PAGES) {
            return $this->loadFromCache($boundary);
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

    public function loadFromCache(GetEventsBoundary $boundary)
    {
        if ($boundary->getRemoved() === 'removed') {
            $eventCache = new RemovedEventCache();
            $total = EventQuery::query()->removed($boundary->getRemoved())->count();
        } else {
            $eventCache = new PublishedEventCache();
            $total = EventQuery::query()->count();
        }

        $events = $eventCache->getEvents($boundary, $total);

        if ($events->isEmpty()) {
            $events = EventQuery::query()
                ->removed($boundary->getRemoved())
                ->orderByDesc('updated_at')
                ->with(['tags', 'links', 'removedLinks', 'category'])
                ->offset(Event::paginationStart($boundary->getCurrentPage()))
                ->limit(15)
                ->get();

            $eventCache->setEvents($events, $boundary);
        }

        $this->loadUserSaved($events, $boundary);

        return $this->makePaginator($events, $boundary, $total);
    }

    private function loadUserSaved(Collection $events, GetEventsBoundary $boundary)
    {
        $events->when($boundary->getUserId(), function (Collection $query, $userId) {
            return $query->load('userSaved')->where('user_id', $userId);
        });
    }

    private function makePaginator(Collection $events, GetEventsBoundary $boundary, int $total)
    {
        $paginator = new Paginator($events, $total, 15, $boundary->getCurrentPage());
        $route = $boundary->getRemoved() === 'removed'
            ? route('events', ['removed' => 'removed'])
            : route('events');

        return $paginator->withPath($route);
    }
}