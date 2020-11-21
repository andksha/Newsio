<?php

namespace Newsio\Model\Cache;

use Illuminate\Database\Eloquent\Collection;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Lib\PRedis;
use Newsio\Model\Event;

final class EventCache implements EventCacheRepository
{
    private PRedis $client;
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

            return $events->sortBy(function ($a, $b) {
                return ($a->updated_at ?? '') <=> ($b->updated_at ?? '');
            });
        } else {
            return new Collection();
        }
    }

    public function setEvents(Collection $events, GetEventsBoundary $boundary)
    {
        $toSet = [];

        if ($events->isEmpty()) {
            return [];
        }

        if (!$this->client->exists($this->listKey)) {
            $this->client->rpush($this->listKey, range(0, $this->eventsToCache - 1));
        }

        foreach ($events as $index => $event) {
            $key = $this->idKey . $event->id;
            $toSet[$key] = serialize($event);
            $this->client->lset($this->listKey, $index, $key);
        }

        $result = $this->client->hmset($this->hsetKey, $toSet);
        $this->client->expire($this->hsetKey, $this->ttl);
        $this->client->expire($this->listKey, $this->ttl);

        return $result;
    }

    public function addOrUpdateEvent(Event $event): array
    {
        $newKey = $this->idKey . $event->id;
        $keyToRemove = $this->client->lindex($this->listKey, $this->eventsToCache - 1);

        if ($keyToRemove === null) {
            return [];
        }

        $this->client->multi();

        $this->client->lpush($this->listKey, [$newKey]);
        $this->client->hset($this->hsetKey, $newKey, $event);
        $this->client->rpop($this->listKey);
        $this->client->hdel($this->hsetKey, [$keyToRemove]);

        return $this->client->exec();
    }
}