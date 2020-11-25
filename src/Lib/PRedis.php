<?php

namespace Newsio\Lib;

use Closure;
use ErrorException;
use Newsio\Contract\RedisClient;
use Predis\Client;

final class PRedis implements RedisClient
{
    private Client $client;

    public function __construct()
    {
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
        return (bool) $this->client->set($key, serialize($value));
    }

    public function setex(string $key, $value, int $ttl): bool
    {
        return (bool) $this->client->setex($key, $ttl, serialize($value));
    }

    public function mset(array $dictionary)
    {
        return $this->client->mset($dictionary);
    }

    public function get(string $key)
    {
        $value = $this->client->get($key);

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

    public function hset(string $key, string $field, $value)
    {
        $serializedValue = serialize($value);

        return $this->client->hset($key, $field, $serializedValue);
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

    public function hget(string $key, string $field)
    {
        return unserialize($this->client->hget($key, $field));
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

    public function hgetall(string $key)
    {
        $result = $this->client->hgetall($key);

        foreach ($result as $key => $value) {
            try {
                $result[$key] = unserialize($value);
            } catch (ErrorException $e) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function hdel(string $key, array $fields)
    {
        return $this->client->hdel($key, $fields);
    }

    public function hlen(string $key): int
    {
        return $this->client->hlen($key);
    }

    public function hexists(string $key, string $field): bool
    {
        return (bool) $this->client->hexists($key, $field);
    }

    public function hincrby(string $key, string $field, int $increment)
    {
        return $this->client->hincrby($key, $field, $increment);
    }

    public function hremember(string $pluralKey, string $key, Closure $closure, int $ttl = 3600)
    {
        $value = $this->hmget($pluralKey, [$key]);

        if (isset($value[0]) && $value[0] !== false) {
            return $value[0];
        }

        $value = $closure();
        $this->hmset($pluralKey, [$key => $value]);
        $this->expire($pluralKey, $ttl);

        return $value;
    }

    public function remember(string $key, Closure $closure, int $ttl = 3600)
    {
        $value = $this->get($key);

        if (!$value) {
            $value = $closure();

            $this->setex($key, $value, $ttl);

            return $value;
        }

        return $value;
    }

    public function zadd(string $key, array $dictionary)
    {
        foreach ($dictionary as $dkey => $value) {
            $dictionary[$dkey] = serialize($value);
        }

        return $this->client->zadd($key, array_flip($dictionary));
    }

    public function lindex(string $key, int $index): ?string
    {
        return $this->client->lindex($key, $index);
    }

    public function lset(string $key, int $index, $value)
    {
        return $this->client->lset($key, $index, $value);
    }

    public function lpush(string $key, array $values)
    {
        return $this->client->lpush($key, $values);
    }

    public function lrange(string $key, int $start, int $stop): array
    {
        return $this->client->lrange($key, $start, $stop);
    }

    public function rpush(string $key, $values)
    {
        return $this->client->rpush($key, $values);
    }

    public function llen(string $key)
    {
        return $this->client->llen($key);
    }

    public function lrem(string $key, int $count, $value)
    {
        return $this->client->lrem($key, $count, $value);
    }

    public function rpop(string $key)
    {
        return $this->client->rpop($key);
    }

    public function del(array $keys)
    {
        return $this->client->del($keys);
    }

}