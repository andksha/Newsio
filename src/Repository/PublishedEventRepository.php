<?php

namespace Newsio\Repository;

use App\Jobs\CachePublishedEvents;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;
use Newsio\Model\Cache\PublishedEventCache;
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
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserId())
            ->with(['tags', 'links', 'removedLinks', 'category'])
            ->paginate(self::PER_PAGE);
    }

    protected function getTotal(): int
    {
        return EventQuery::query()->count();
    }

    protected function getRoute(): string
    {
        return route('events');
    }

}