<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Model\Moderator
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moderator whereUpdatedAt($value)
 */
class Moderator extends Authenticatable implements JWTSubject
{
    protected $connection = 'pgsql2';

    protected $table = 'moderators';

    protected $fillable = [
        'email', 'password'
    ];

    public function verify()
    {
        $this->email_verified_at = Carbon::now();

        return $this;
    }

    public function changePassword(string $password)
    {
        $this->password = Hash::make($password);

        return $this;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}