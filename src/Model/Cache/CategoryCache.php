<?php

namespace Newsio\Model\Cache;

use Closure;
use Newsio\Contract\Cache;
use Newsio\Lib\PRedis;

final class CategoryCache implements Cache
{
    private Cache $baseCache;

    public function __construct()
    {
        $this->baseCache = new BaseCache(new PRedis(), 'categories', 604800); // one week
    }

    public function hremember(string $key, Closure $closure)
    {
        return $this->baseCache->hremember($key, $closure);
    }

    public function remember(string $key, Closure $closure)
    {
        return $this->baseCache->remember($key, $closure);
    }
}