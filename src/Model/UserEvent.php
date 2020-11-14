<?php

namespace Newsio\Model;

/**
 * Newsio\Model\UserEvent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEvent query()
 */
final class UserEvent extends BaseModel
{
    protected $table = 'users_events';

    protected $fillable = [
        'user_id',
        'event_id'
    ];
}