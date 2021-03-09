<?php

namespace App\Event;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Newsio\Model\Event;
use Newsio\Model\Operation;

class LinkRestoredEvent extends BaseOperationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(Event $event)
    {
        $this->operationType = Operation::OT_RESTORED;
        $this->modelType = Operation::MT_EVENT;
        $this->modelId = $event->id;
        $this->model = json_encode($event);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
