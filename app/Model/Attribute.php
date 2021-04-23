<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

final class Attribute extends Model
{
    protected $table = 'attributes';

    protected $fillable = [
        'category_id',
        'name',
        'type',
        'slug',
    ];
}