<?php

namespace Newsio\Model;

class Website extends BaseModel
{
    protected $table = 'websites';

    protected $visible = [
        'domain',
        'approved',
        'reason'
    ];
}