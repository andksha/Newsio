<?php

namespace App\Model;

use Newsio\Model\BaseModel;

/**
 * App\Model\ModeratorConfirmation
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModeratorConfirmation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModeratorConfirmation extends BaseModel
{
    protected $connection = 'pgsql2';

    protected $table = 'moderator_confirmations';

    protected $fillable = [
        'email', 'token'
    ];
}