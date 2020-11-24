<?php

namespace Newsio\Model\Cache;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\RedisClient;
use Newsio\Lib\PRedis;
use Newsio\Model\Event;

final class EventCache implements EventCacheRepository
{
    private RedisClient $client;
    private string $singularKey;
    private string $pluralKey;
    private string $listKey;
    private string $hsetKey;
    private string $idKey;
    private int $ttl;
    private int $eventsToCache;

    public function __construct(string $singularKey = 'event', string $pluralKey = 'events', int $ttl = 3600, int $eventsToCache = 150)
    {
        $this->singularKey = $singularKey;
        $this->pluralKey = $pluralKey;
        $this->listKey = $this->pluralKey . '.list';
        $this->hsetKey = $this->pluralKey . '.hset';
        $this->idKey = 'id.';
        $this->ttl = $ttl;

        $this->client = new PRedis();
        $this->eventsToCache = $eventsToCache;
    }

    public function getEvents(GetEventsBoundary $boundary): Collection
    {
        $start = Event::paginationStart($boundary->getCurrentPage());
        $stop = Event::paginationStop($boundary->getCurrentPage());

        $eventsKeys = array_filter($this->client->lrange($this->listKey, $start, $stop), function ($value) {
            return strpos($value, $this->idKey) !== false;
        });

        if (count($eventsKeys) > 0) {
            $searchResults = $this->client->hmget($this->hsetKey, $eventsKeys);
            $events = new Collection($searchResults);

            return $events->sortByDesc(function ($event) {
                return $event->updated_at;
            });
        } else {
            return new Collection();
        }
    }

    public function setEvents(Collection $events)
    {
        if ($events->isEmpty()) {
            return [];
        }

        $toSet = [];
        $listKeys = [];

        $this->client->del([$this->listKey, $this->hsetKey]);

        foreach ($events as  $event) {
            $key = $this->idKey . $event->id;
            $toSet[$key] = serialize($event);
            $listKeys[] = $key;
        }

        $this->client->multi();

        $this->client->hmset($this->hsetKey, $toSet);
        $this->client->rpush($this->listKey, $listKeys);

        if ($events->count() < 150) {
            $this->client->rpush($this->listKey, array_fill(0, $this->eventsToCache - $events->count(), 1));
        }

        $this->client->expire($this->hsetKey, $this->ttl);
        $this->client->expire($this->listKey, $this->ttl);

        return $this->client->exec();
    }

    public function addOrUpdateEvent(Event $event): array
    {
        $eventKey = $this->idKey . $event->id;

        if (!$this->cacheIsLoaded()) {
            return [];
        }

        if ($this->client->hexists($this->hsetKey, $eventKey)) {
            return $this->updateEvent($event, $eventKey);
        }

        return $this->addEvent($event, $eventKey);
    }

    private function addEvent(Event $event, $eventKey)
    {
        $keyToRemove = $this->client->lindex($this->listKey, $this->eventsToCache - 1);

        $this->client->multi();

        $this->client->lpush($this->listKey, [$eventKey]);
        $this->client->hset($this->hsetKey, $eventKey, $event);
        $this->client->rpop($this->listKey);
        $this->client->hdel($this->hsetKey, [$keyToRemove]);

        return $this->client->exec();
    }

    private function updateEvent(Event $event, $eventKey)
    {
        $this->client->multi();

        $this->client->lrem($this->listKey, 1, $eventKey);
        $this->client->hdel($this->hsetKey, [$eventKey]);
        $this->client->hset($this->hsetKey, $eventKey, $event);
        $this->client->lpush($this->listKey, [$eventKey]);

        return $this->client->exec();
    }

    public function removeEvent(Event $event)
    {
        $eventKey = $this->idKey . $event->id;

        $this->client->multi();

        $this->client->lrem($this->listKey, 1, $eventKey);
        $this->client->hdel($this->hsetKey, [$eventKey]);

        return $this->client->exec();
    }

    public function pushLastEvent(Closure $closure)
    {
        $listLen = $this->client->llen($this->listKey);

        if (!$this->cacheIsLoaded()) {
            return [];
        }

        $this->client->multi();

        if ($listLen < $this->eventsToCache) {
            $lastEvent = $closure();

            if ($lastEvent) {
                $lastEventKey = $this->idKey . $lastEvent->id;

                $this->client->rpush($this->listKey, $lastEventKey);
                $this->client->hset($this->hsetKey, $lastEventKey, $lastEvent);
            }
        }

        return $this->client->exec();
    }

    public function cacheIsLoaded(): bool
    {
        return $this->client->exists($this->listKey)
            && $this->client->exists($this->hsetKey)
            && ($this->client->llen($this->listKey) > 0)
            && ($this->client->hlen($this->hsetKey) > 0);
    }

    public function getTotal(Closure $closure): int
    {
        return $this->client->remember($this->pluralKey . '.total', $closure, 300); // 5 minutes
    }
}