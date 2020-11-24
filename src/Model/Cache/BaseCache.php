<?php

namespace Newsio\Model\Cache;

use Closure;
use Newsio\Contract\Cache;
use Newsio\Contract\RedisClient;
use Newsio\Lib\PRedis;

final class BaseCache implements Cache
{
    private RedisClient $cacheClient;

    public function __construct(PRedis $cacheClient)
    {
        $this->cacheClient = $cacheClient;
    }

    public function hremember(string $pluralKey, string $key, Closure $closure, int $ttl = 3600)
    {
        $value = $this->cacheClient->hmget($pluralKey, [$key]);

        if (isset($value[0]) && $value[0] !== false) {
            return $value[0];
        }

        $value = $closure();
        $this->cacheClient->hmset($pluralKey, [$key => $value]);
        $this->cacheClient->expire($pluralKey, $ttl);

        return $value;
    }

    public function remember(string $key, Closure $closure, int $ttl = 3600)
    {
        $value = $this->cacheClient->get($key);

        if (!$value) {
            $value = $closure();
            $this->cacheClient->setex($key, $value, $ttl);

            return $value;
        }

        return $value;
    }

}