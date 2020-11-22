<?php

namespace Newsio\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonSerializable;
use Newsio\Exception\InvalidOperationException;

/**
 * Newsio\Model\Event
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Database\Eloquent\Collection|\Newsio\Model\Tag[] $tags
 * @property \Illuminate\Database\Eloquent\Collection|\Newsio\Model\Link[] $links
 * @property \Illuminate\Database\Eloquent\Collection|\Newsio\Model\Link[] $removedLinks
 * @property int $user_id
 * @property int $category_id
 * @property string $reason
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Newsio\Model\Category|null $category
 * @property-read int|null $links_count
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Query\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Event withoutTrashed()
 */
class Event extends BaseModel implements JsonSerializable
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'title', 'user_id', 'category_id', 'view_count'
    ];

    protected $visible = [
        'id',
        'title',
        'tags',
        'links',
        'removed_links',
        'reason',
        'view_count',
        'category_id',
        'updated_at',
        'deleted_at'
    ];

    public const MAX_CACHED_PAGES = 10;
    public const DEFAULT_RELATIONS = [
        'tags',
        'links',
        'removedLinks',
    ];


    public static function paginationStart(int $page): int
    {
        return ($page - 1) * 15;
    }

    public static function paginationStop(int $page): int
    {
        return ($page * 15) - 1;
    }

    public function remove(string $reason): Event
    {
        $this->reason = $reason;
        $this->deleted_at = Carbon::now();
        $this->links()->update(['reason' => 'Removed with event']);
        $this->links()->delete();
        $this->save();

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidOperationException
     */
    public function restore()
    {
        if (!$this->links->where('deleted_at', null)->where('reason', '')->first()) {
            throw new InvalidOperationException('Event can\'t be restored without approved links');
        }

        $this->reason = '';
        $this->deleted_at = null;
        $this->save();

        return $this;
    }

    public function incrementViewCount(): bool
    {
        $this->timestamps = false;
        $this->view_count = $this->view_count + 1;
        return $this->save(['timestamps' => false]);
    }

    /** Relations */

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'events_tags', 'event_id', 'tag_id');
    }

    public function userSaved(): HasOne
    {
        return $this->hasOne(UserEvent::class, 'event_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'event_id');
    }

    public function removedLinks(): HasMany
    {
        return $this->hasMany(Link::class, 'event_id')->onlyTrashed();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function jsonSerialize()
    {
        // @TODO: add a resource for each operation (creating, removing, getting)
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'tags'          => $this->tags->pluck('name'),
            'links'         => $this->links->pluck('content'),
            'removed_links' => $this->removedLinks,
            'deleted_at'    => $this->deleted_at
        ];
    }
}
