<?php

namespace Newsio\Model;

use Illuminate\Database\Eloquent\Model;
use Newsio\Lib\Snowflake;

abstract class BaseModel extends Model
{
    public function fill(array $attributes)
    {
        if (!$this->id) {
            $snowflake = new Snowflake();
            $this->id = $snowflake->id();
        }
        parent::fill($attributes);
    }
}
