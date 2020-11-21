<?php

namespace Newsio\Repository;

use App\Jobs\CacheRemovedEvents;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Query\EventQuery;

final class RemovedEventRepository extends BaseEventRepository implements EventRepository
{
    public const TO_CACHE = 150;
    public const PER_PAGE = 15;

    protected function getEventCache(): EventCacheRepository
    {
        return new RemovedEventCache();
    }

    public function getJob(EventCacheRepository $eventCache, GetEventsBoundary $boundary)
    {
        return new CacheRemovedEvents($eventCache, $boundary);
    }

    protected function getEventsFromDB(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        return EventQuery::query()
            ->removed($boundary->getRemoved())
            ->orderByDesc('updated_at')
            ->withUserSaved($boundary->getUserId())
            ->with(['tags', 'links', 'removedLinks', 'category'])
            ->paginate(self::PER_PAGE);
    }

    protected function getTotal(): int
    {
        return EventQuery::query()->removed('removed')->count();
    }

    protected function getRoute(): string
    {
        return route('events', ['removed' => 'removed']);
    }

}