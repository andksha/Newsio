<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Query\EventQuery;
use Newsio\Repository\PublishedEventRepository;

class CachePublishedEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private EventCacheRepository $eventCache;
    private GetEventsBoundary $boundary;

    /**
     * Create a new job instance.
     *
     * @param EventCacheRepository $eventCache
     * @param GetEventsBoundary $boundary
     */
    public function __construct(EventCacheRepository $eventCache, GetEventsBoundary $boundary)
    {
        $this->eventCache = $eventCache;
        $this->boundary = $boundary;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $events = EventQuery::query()
            ->orderByDesc('updated_at')
            ->with(['tags', 'links', 'removedLinks', 'category'])
            ->limit(PublishedEventRepository::TO_CACHE)
            ->get();

        $this->eventCache->setEvents($events);
    }
}
