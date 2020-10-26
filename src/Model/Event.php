<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class Event extends BaseModel
{
    use SoftDeletes;

    protected $table = 'events';
    private string $reason = '';

    protected $fillable = [
        'title', 'tags', 'links', 'category_id'
    ];

    protected $visible = [
        'title', 'tags', 'links', 'reason', 'category_id', 'updated_at'
    ];

    /**
     * @param string $reason
     * @return Event
     * @throws Exception
     */
    public function remove(string $reason): Event
    {
        $this->reason = $reason;
        $this->delete();

        return $this;
    }

    /** Relations */

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'events_tags', 'event_id', 'tag_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'event_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'category_id');
    }
}
