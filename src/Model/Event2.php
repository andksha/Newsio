<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Model;
use Newsio\ValueObject\EventLogo;

final class Event2 extends Model
{
    protected $table = 'events_1';

    protected $fillable = ['event_logo'];

    protected $casts = [
        'event_logo' => EventLogo::class,
    ];
//
//    public function getEventLogoAttribute()
//    {
//        return new EventLogo(json_decode($this->attributes['event_logo'], true));
//    }
//
//    public function setEventLogoAttribute(array $eventLogo)
//    {
//        $this->attributes['event_logo'] = json_encode($eventLogo);
//    }
}