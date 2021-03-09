<?php

namespace App\Providers;

use App\Event\EventRemovedEvent;
use App\Event\EventRestoredEvent;
use App\Event\LinkRemovedEvent;
use App\Event\LinkRestoredEvent;
use App\Event\LinksAddedEvent;
use App\Event\ModeratorCreatedEvent;
use App\Event\EventCreatedEvent;
use App\Event\RegisteredEvent;
use App\Listener\ModeratorCreatedListener;
use App\Listener\OperationListener;
use App\Listener\RegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RegisteredEvent::class => [
            RegisteredListener::class
        ],
        ModeratorCreatedEvent::class => [
            ModeratorCreatedListener::class
        ],
        EventCreatedEvent::class => [
            OperationListener::class
        ],
        EventRemovedEvent::class => [
            OperationListener::class
        ],
        EventRestoredEvent::class => [
            OperationListener::class
        ],
        LinksAddedEvent::class => [
            OperationListener::class
        ],
        LinkRemovedEvent::class => [
            OperationListener::class
        ],
        LinkRestoredEvent::class => [
            OperationListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
