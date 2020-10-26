<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends BaseModel
{
    use SoftDeletes;

    protected $table = 'links';

    protected $fillable = ['content'];

    protected $visible = ['content', 'reason'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
