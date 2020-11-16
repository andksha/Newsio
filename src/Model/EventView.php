<?php

namespace Newsio\Model;

final class EventView extends BaseModel
{
    protected $table = 'events_views';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_identifier'
    ];

    protected $visible = [
        'event_id',
        'user_identifier'
    ];
}