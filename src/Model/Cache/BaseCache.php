<?php

namespace Newsio\Model\Cache;

use Closure;
use Newsio\Contract\Cache;
use Newsio\Contract\RedisClient;

final class BaseCache implements Cache
{
    private RedisClient $cacheClient;
    private string $pluralKey;
    private int $ttl;

    public function __construct(RedisClient $cacheClient, string $pluralKey, int $ttl)
    {
        $this->cacheClient = $cacheClient;
        $this->pluralKey = $pluralKey;
        $this->ttl = $ttl;
    }

    public function hremember(string $key, Closure $closure)
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

    public function remember(string $key, Closure $closure)
    {
        $value = $this->cacheClient->get($key);

        if (!$value) {
            $value = $closure();
            $this->cacheClient->setex($key, $value, $this->ttl);

            return $value;
        }

        return $value;
    }

}