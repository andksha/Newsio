<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * Newsio\Model\Website
 *
 * @property int $id
 * @property string $domain
 * @property bool $approved
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Website approved()
 * @method static Builder|Website newModelQuery()
 * @method static Builder|Website newQuery()
 * @method static Builder|Website query()
 * @method static Builder|Website whereApproved($value)
 * @method static Builder|Website whereCreatedAt($value)
 * @method static Builder|Website whereDomain($value)
 * @method static Builder|Website whereId($value)
 * @method static Builder|Website whereReason($value)
 * @method static Builder|Website whereUpdatedAt($value)
 */
class Website extends BaseModel
{
    protected $table = 'websites';

    protected $visible = [
        'domain',
        'approved',
        'reason'
    ];

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }
}