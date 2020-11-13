<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTag extends BaseModel
{
    protected $table = 'events_tags';

    protected $visible = [
        'tag'
    ];

    public function tag():BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
