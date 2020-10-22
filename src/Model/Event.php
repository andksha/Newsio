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
    private int $category;

    protected $fillable = [
        'title', 'tag', 'link', 'category'
    ];

    public function remove(string $reason)
    {
        $this->removed = true;
        $this->reason = $reason;
    }
}
