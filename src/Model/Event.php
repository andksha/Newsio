<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends BaseModel
{
    use SoftDeletes;

    protected $table = 'events';
    private bool $removed = false;
    private string $reason = '';

    protected $fillable = [
        'title', 'tag', 'link', 'category_id'
    ];

    protected $visible = [
        'title', 'tag', 'link', 'category_id', 'updated_at'
    ];

    public function remove(string $reason)
    {
        $this->removed = true;
        $this->reason = $reason;
    }

    /** Relations */

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id');
    }
}
