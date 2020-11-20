<?php

namespace Newsio\Contract;

use Closure;

interface RedisClient
{
    public function exists(string $key): bool;

    public function multi();

    public function exec();

    public function pipeline(Closure $closure);

    public function set(string $key, $value): bool;

    public function setex(string $key, $value, int $ttl): bool;

    public function mset(array $dictionary);

    public function hmset(string $key, array $dictionary);

    public function get(string $key);

    public function mget(array $keys);

    public function hmget(string $key, array $fields);

    public function zadd(string $key, array $dictionary);

    public function lset(string $key, int $index, $value);

    public function lrange(string $key, int $start, int $stop): array;

    public function rpush(string $key, $values);

    public function del(array $keys);

}