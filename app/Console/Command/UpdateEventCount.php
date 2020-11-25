<?php

namespace App\Console\Command;

use Illuminate\Console\Command;
use Newsio\Repository\EventViewRepository;

final class UpdateEventCount extends Command
{
    protected $signature = 'events.view.count:update';
    protected $description = 'Update events view count in database';
    private EventViewRepository $eventViewRepository;

    public function __construct(EventViewRepository $eventViewRepository)
    {
        parent::__construct();

        $this->eventViewRepository = $eventViewRepository;
    }

    public function handle()
    {
        $this->eventViewRepository->updateEvents();
    }
}