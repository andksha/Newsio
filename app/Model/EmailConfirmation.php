<?php

namespace App\Model;

use Newsio\Model\BaseModel;

class EmailConfirmation extends BaseModel
{
    protected $table = 'email_confirmations';

    protected $fillable = [
        'email', 'token'
    ];
}