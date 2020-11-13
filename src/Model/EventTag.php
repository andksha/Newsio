<?php

namespace Newsio\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public static function getPopularAndRareTags(): array
    {
        return [
            'popular' => self::popularTags(),
            'rare' => self::rareTags()
        ];
    }

    // @TODO: caching
    public static function popularTags(): Collection
    {
        return static::query()->select('tag_id', DB::raw('count(*) as total'))
            ->where('created_at', '>', Carbon::now()->subDay())
            ->groupBy('tag_id')
            ->orderByDesc('total')
            ->limit(15)
            ->get();
    }

    // @TODO: caching
    public static function rareTags(): Collection
    {
        return static::query()->select('tag_id', DB::raw('count(*) as total'))
            ->where('created_at', '>', Carbon::now()->subDay())
            ->groupBy('tag_id')
            ->orderBy('total')
            ->limit(15)
            ->get();
    }
}
