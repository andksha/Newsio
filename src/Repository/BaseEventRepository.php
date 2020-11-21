<?php

namespace Newsio\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;

abstract class BaseEventRepository implements EventRepository
{
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        $eventCache = $this->getEventCache();
        $events = $eventCache->getEvents($boundary);

        if ($events->isEmpty()) {
            dispatch($this->getJob($eventCache, $boundary));

            return $this->getEventsFromDB($boundary);
        }

        $this->loadUserSaved($events, $boundary);

        return $this->makePaginator($events, $boundary, $this->getTotal(), $this->getRoute());
    }

    abstract protected function getEventCache(): EventCacheRepository;
    abstract protected function getJob(EventCacheRepository $eventCache, GetEventsBoundary $boundary);
    abstract protected function getEventsFromDB(GetEventsBoundary $boundary): LengthAwarePaginator;
    abstract protected function getTotal(): int;
    abstract protected function getRoute(): string;

    private function loadUserSaved(Collection $events, GetEventsBoundary $boundary)
    {
        $events->when($boundary->getUserId(), function (Collection $query, $userId) {
            return $query->load('userSaved')->where('user_id', $userId);
        });
    }

    private function makePaginator(Collection $events, GetEventsBoundary $boundary, int $total, string $route)
    {
        $paginator = new Paginator($events, $total, 15, $boundary->getCurrentPage());

        return $paginator->withPath($route);
    }
}