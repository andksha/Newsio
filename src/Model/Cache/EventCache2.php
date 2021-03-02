<?php

namespace Newsio\Model\Cache;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Contract\RedisClient;
use Newsio\Lib\PRedis;
use Newsio\Model\Event;
use Newsio\Repository\BaseEventRepository;

final class EventCache2 implements EventCacheRepository
{
    private RedisClient $client;
    private string $hsetKey;
    private string $idKey;
    private int $ttl;
    private string $pluralKey;
    private int $eventsToCache;

    public function __construct(string $pluralKey = 'events2', int $ttl = 3600, int $eventsToCache = 150)
    {
        $this->client = new PRedis();
        $this->pluralKey = $pluralKey;
        $this->hsetKey = $pluralKey . '.hset';
        $this->idKey = 'id.';
        $this->ttl = $ttl;
        $this->eventsToCache = $eventsToCache;
    }

    public function getEvents(GetEventsBoundary $getEventsBoundary): Collection
    {
        $start = BaseEventRepository::paginationStart($getEventsBoundary->getCurrentPage());

        $events = new Collection($this->client->hgetall($this->hsetKey));
        $sortedEvents = $events->sortByDesc(function ($event) {
            return $event->updated_at;
        });

        return $sortedEvents->slice($start, 15);
    }

    public function setEvents(Collection $events)
    {
        if ($events->isEmpty()) {
            return [];
        }

        $toSet = [];
        $this->client->del([$this->hsetKey]);

        foreach ($events as  $event) {
            $key = $this->idKey . $event->id;
            $toSet[$key] = serialize($event);
        }

        $this->client->hmset($this->hsetKey, $toSet);

        return $this->client->expire($this->hsetKey, $this->ttl);
    }

    public function addOrUpdateEvent(Event $event): array
    {
        $eventKey = $this->idKey . $event->id;

        if (!$this->cacheIsLoaded()) {
            return [];
        }

        if ($this->client->hexists($this->hsetKey, $eventKey)) {
            $this->client->hdel($this->hsetKey, [$eventKey]);
            return $this->client->hset($this->hsetKey, $eventKey, $event);
        }

        return $this->client->hset($this->hsetKey, $eventKey, $event);
    }

    public function removeEvent(Event $event)
    {
        $this->client->hdel($this->hsetKey, [$this->idKey . $event->id]);
    }

    public function pushLastEvent(Closure $closure)
    {
        if (!$this->cacheIsLoaded()) {
            return [];
        }

        $hsetLen = $this->client->hlen($this->hsetKey);

        if ($hsetLen < $this->eventsToCache) {
            $lastEvent = $closure();

            if ($lastEvent) {
                return $this->client->hset($this->hsetKey, $this->idKey . $lastEvent->id, $lastEvent);
            }
        }

        return [];
    }

    public function cacheIsLoaded(): bool
    {
        return $this->client->exists($this->hsetKey)
            && ($this->client->hlen($this->hsetKey) > 0);
    }

    public function getTotal(Closure $closure): int
    {
        return $this->client->remember($this->pluralKey . '.total', $closure, 300); // 5 minutes
    }

}