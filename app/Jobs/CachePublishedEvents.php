<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Model\Event;
use Newsio\Query\EventQuery;
use Newsio\Repository\PublishedEventRepository;

class CachePublishedEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private EventCacheRepository $eventCache;
    private GetEventsBoundary $getEventsBoundary;

    /**
     * Create a new job instance.
     *
     * @param EventCacheRepository $eventCache
     * @param GetEventsBoundary $getEventsBoundary
     */
    public function __construct(EventCacheRepository $eventCache, GetEventsBoundary $getEventsBoundary)
    {
        $this->eventCache = $eventCache;
        $this->getEventsBoundary = $getEventsBoundary;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $events = EventQuery::query()
            ->defaultOrder()
            ->with(Event::DEFAULT_RELATIONS)
            ->limit(PublishedEventRepository::TO_CACHE)
            ->get();

        $this->eventCache->setEvents($events);
    }
}
