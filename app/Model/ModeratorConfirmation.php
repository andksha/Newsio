<?php

namespace App\Model;

use Newsio\Model\BaseModel;

class ModeratorConfirmation extends BaseModel
{
    protected $table = 'moderator_confirmations';

    protected $fillable = [
        'email', 'token'
    ];
}