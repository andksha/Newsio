<?php

namespace App\Model;

use Newsio\Model\BaseModel;

/**
 * App\Model\EmailConfirmation
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailConfirmation whereUpdatedAt($value)
 */
class EmailConfirmation extends BaseModel
{
    protected $table = 'email_confirmations';

    protected $fillable = [
        'email', 'token'
    ];
}