<?php

namespace Newsio\Model;

class Event extends BaseModel
{
    protected $table = 'events';
    private string $title;
    private string $tag;
    private string $link;
    private bool $removed = false;
    private string $reason = '';
    private int $category_id;

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
