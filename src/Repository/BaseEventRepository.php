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
    public const MAX_CACHED_PAGES = 10;

    public static function paginationStart(int $page): int
    {
        return ($page - 1) * 15;
    }

    public static function paginationStop(int $page): int
    {
        return ($page * 15) - 1;
    }

    public function getEvents(GetEventsBoundary $getEventsBoundary): LengthAwarePaginator
    {
        $eventCache = $this->getEventCache();

        if ($eventCache->cacheIsLoaded()) {
            $events = $eventCache->getEvents($getEventsBoundary);
            $events->when($getEventsBoundary->getUserId(), function (Collection $query, $userId) {
                return $query->load('userSaved')->where('user_id', $userId);
            });

            $paginator = new Paginator($events, $this->getTotal($eventCache), 15, $getEventsBoundary->getCurrentPage());

            return $paginator->withPath($this->getRoute());
        }

        dispatch($this->getCacheJob($eventCache, $getEventsBoundary));

        return $this->getEventsFromDB($getEventsBoundary);
    }

    abstract protected function getEventCache(): EventCacheRepository;
    abstract protected function getCacheJob(EventCacheRepository $eventCache, GetEventsBoundary $getEventsBoundary);
    abstract protected function getEventsFromDB(GetEventsBoundary $getEventsBoundary): LengthAwarePaginator;
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