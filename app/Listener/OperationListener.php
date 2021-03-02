<?php

namespace App\Listener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Newsio\Contract\OperationEvent;
use Newsio\EventHandler\OperationHandler;

class OperationListener
{
    private OperationHandler $handler;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->handler = new OperationHandler();
    }

    /**
     * Handle the event.
     *
     * @param OperationEvent $event
     * @return void
     */
    public function handle(OperationEvent $event)
    {
        $this->handler->handle($event);
    }
}
