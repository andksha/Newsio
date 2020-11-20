<?php

namespace Newsio\Model\Cache;

use Closure;
use Newsio\Lib\PRedis;

final class TagCache
{
    private PRedis $cacheClient;
    private string $pluralKey = 'tags';
    private int $ttl;

    public function __construct()
    {
        $this->cacheClient = new PRedis();
        $this->ttl = 60*60;
    }

    public function remember(string $key, Closure $closure)
    {
        $value = $this->cacheClient->hmget($this->pluralKey, [$key]);

        if (isset($value[0]) && $value[0] !== false) {
            return $value[0];
        }

        $value = $closure();
        $this->cacheClient->hmset($this->pluralKey, [$key => $value]);
        $this->cacheClient->expire($this->pluralKey, $this->ttl);

        return $value;
    }

}