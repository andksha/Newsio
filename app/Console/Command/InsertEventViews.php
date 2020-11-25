<?php

namespace App\Console\Command;

use Illuminate\Console\Command;
use Newsio\Model\Cache\EventViewCache;
use Newsio\Model\EventView;

final class InsertEventViews extends Command
{
    protected $signature = 'event.views:insert';
    protected $description = 'Insert new event views from cache to db if max max EventView limit is reached';
    private EventViewCache $eventViewCache;

    public function __construct(EventViewCache $eventViewCache)
    {
        parent::__construct();
        $this->eventViewCache = $eventViewCache;
    }

    public function handle(): void
    {
        if ($this->eventViewCache->maxCachedReached()) {
            $eventViews = $this->eventViewCache->getEventViews();
            EventView::query()->insert($eventViews);
            $this->eventViewCache->clearEventViews();
        }
    }
}