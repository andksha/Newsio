<?php

namespace Newsio\Repository;

use App\Jobs\CacheRemovedEvents;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\EventRepository;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;

final class RemovedEventRepository extends BaseEventRepository implements EventRepository
{
    public const TO_CACHE = 150;
    public const PER_PAGE = 15;

    protected function getEventCache(): EventCacheRepository
    {
        return new RemovedEventCache();
    }

    public function getCacheJob(EventCacheRepository $eventCache, GetEventsBoundary $getEventsBoundary)
    {
        return new CacheRemovedEvents($eventCache, $getEventsBoundary);
    }

    protected function getEventsFromDB(GetEventsBoundary $getEventsBoundary): LengthAwarePaginator
    {
        return EventQuery::query()
            ->removed($getEventsBoundary->getRemoved())
            ->defaultOrder()
            ->withUserSaved($getEventsBoundary->getUserId())
            ->with(Event::DEFAULT_RELATIONS)
            ->paginate(self::PER_PAGE);
    }

    protected function getTotal(EventCacheRepository $eventCacheRepository): int
    {
        return $eventCacheRepository->getTotal(function () {
            return EventQuery::query()->removed('removed')->count();
        });
    }

    protected function getRoute(): string
    {
        return route('events', ['removed' => 'removed']);
    }
}