<?php

namespace Newsio\Repository;

use App\Jobs\CachePublishedEvents;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;

final class PublishedEventRepository extends BaseEventRepository implements EventRepository
{
    public const TO_CACHE = 150;
    public const PER_PAGE = 15;

    protected function getEventCache(): EventCacheRepository
    {
        return new PublishedEventCache();
    }

    protected function getCacheJob(EventCacheRepository $eventCache, GetEventsBoundary $boundary)
    {
        return new CachePublishedEvents($eventCache, $boundary);
    }

    protected function getEventsFromDB(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        return EventQuery::query()
            ->defaultOrder()
            ->withUserSaved($boundary->getUserId())
            ->with(Event::DEFAULT_RELATIONS)
            ->paginate(self::PER_PAGE);
    }

    protected function getTotal(EventCacheRepository $eventCache): int
    {
        return $eventCache->getTotal(function () {
            return EventQuery::query()->count();
        });
    }

    protected function getRoute(): string
    {
        return route('events');
    }
}