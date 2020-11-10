<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Moderator extends Authenticatable
{
    protected $table = 'moderators';

    protected $fillable = [
        'email', 'password'
    ];
}