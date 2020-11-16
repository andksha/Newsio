<?php

namespace Newsio\Model;

final class EventView extends BaseModel
{
    protected $table = 'events_views';

    protected $fillable = [
        'event_id',
        'user_identifier'
    ];

    protected $visible = [
        'event_id',
        'user_identifier'
    ];
}