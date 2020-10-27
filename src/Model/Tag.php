<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends BaseModel
{
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = ['name'];

    protected $visible = ['name', 'reason'];

    public function events(): BelongsToMany
    {

    }
}
