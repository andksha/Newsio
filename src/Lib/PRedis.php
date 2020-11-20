<?php

namespace Newsio\Lib;

use Closure;
use Newsio\Contract\RedisClient;
use Predis\Client;

final class PRedis implements RedisClient
{
    private Client $client;
    private string $prefix;

    public function __construct()
    {
        $this->prefix = config('app.name') . '.' . config('database.redis.default.database') . '.';
        $this->client = new Client([
            'host' => config('database.redis.default.host'),
            'port' => config('database.redis.default.port'),
            'password' => config('database.redis.default.password'),
            'database' => config('database.redis.default.database')
        ]);
    }

    public function expire(string $key, int $seconds)
    {
        return $this->client->expire($key, $seconds);
    }

    public function exists(string $key): bool
    {
        return $this->client->exists($key);
    }

    public function multi()
    {
        return $this->client->multi();
    }

    public function exec()
    {
        return $this->client->exec();
    }

    public function pipeline(Closure $closure)
    {
        return $this->client->pipeline($closure);
    }

    public function set(string $key, $value): bool
    {
        return (bool) $this->client->set($this->prefix . $key, serialize($value));
    }

    public function setex(string $key, $value, int $ttl): bool
    {
        return (bool) $this->client->setex($this->prefix . $key, $ttl, serialize($value));
    }

    public function mset(array $dictionary)
    {
        return $this->client->mset($dictionary);
    }

    public function hmset(string $key, array $dictionary)
    {
        $values = [];

        foreach ($dictionary as $dKey => $value) {
            if (is_array($value)) {
                $values[$dKey] = serialize($value);
            } else {
                $values[$dKey] = $value;
            }
        }

        return $this->client->hmset($key, $values);
    }

    public function get(string $key)
    {
        $value = $this->client->get($this->prefix . $key);

        return unserialize($value);
    }

    public function mget(array $keys)
    {
        $serializedEvents = $this->client->mget($keys);
        $result = [];

        foreach ($serializedEvents as $key => $value) {
            $result[$key] = unserialize($value);
        }

        return $result;
    }

    public function hmget(string $key, array $fields)
    {
        $serializedEvents = $this->client->hmget($key, $fields);
        $result = [];

        foreach ($serializedEvents as $key => $value) {
            $result[$key] = unserialize($value);
        }

        return $result;
    }

    public function zadd(string $key, array $dictionary)
    {
        foreach ($dictionary as $dkey => $value) {
            $dictionary[$dkey] = serialize($value);
        }

        return $this->client->zadd($key, array_flip($dictionary));
    }

    public function lset(string $key, int $index, $value)
    {
        return $this->client->lset($key, $index, $value);
    }

    public function lrange(string $key, int $start, int $stop): array
    {
        return $this->client->lrange($key, $start, $stop);
    }

    public function rpush(string $key, $values)
    {
        return $this->client->rpush($key, $values);
    }

    public function del(array $keys)
    {
        return $this->client->del($keys);
    }

}