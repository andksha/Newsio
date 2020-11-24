<?php

namespace Newsio\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;

abstract class BaseEventRepository implements EventRepository
{
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        $eventCache = $this->getEventCache();

        if ($eventCache->cacheIsLoaded()) {
            $events = $eventCache->getEvents($boundary);
            $events->when($boundary->getUserId(), function (Collection $query, $userId) {
                return $query->load('userSaved')->where('user_id', $userId);
            });

            $paginator = new Paginator($events, $this->getTotal($eventCache), 15, $boundary->getCurrentPage());

            return $paginator->withPath($this->getRoute());
        }

        dispatch($this->getCacheJob($eventCache, $boundary));

        return $this->getEventsFromDB($boundary);
    }

    abstract protected function getEventCache(): EventCacheRepository;
    abstract protected function getCacheJob(EventCacheRepository $eventCache, GetEventsBoundary $boundary);
    abstract protected function getEventsFromDB(GetEventsBoundary $boundary): LengthAwarePaginator;
    abstract protected function getTotal(EventCacheRepository $eventCache): int;
    abstract protected function getRoute(): string;

    public function removeEvent(Event $event)
    {
        if ($event->trashed()) {
            $cacheToRemoveFrom = new PublishedEventCache();
            $cacheToAddTo = new RemovedEventCache();
            $removed = '';
        } else {
            $cacheToRemoveFrom = new RemovedEventCache();
            $cacheToAddTo = new PublishedEventCache();
            $removed = 'removed';
        }

        $cacheToRemoveFrom->removeEvent($event);
        $cacheToRemoveFrom->pushLastEvent(function () use ($removed) {
            return EventQuery::query()
                ->removed($removed)
                ->defaultOrder()
                ->with(Event::DEFAULT_RELATIONS)
                ->offset(PublishedEventRepository::TO_CACHE - 1)
                ->first();
        });

        $cacheToAddTo->addOrUpdateEvent($event);
    }
}