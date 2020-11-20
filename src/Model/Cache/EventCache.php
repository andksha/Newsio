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

    public function __construct(string $singularKey, string $pluralKey, int $ttl)
    {
        $this->singularKey = $singularKey;
        $this->pluralKey = $pluralKey;
        $this->listKey = $this->pluralKey . '.list';
        $this->hsetKey = $this->pluralKey . '.hset';
        $this->idKey = 'id.';
        $this->ttl = $ttl;

        $this->client = new PRedis();
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
        $start = Event::paginationStart($boundary->getCurrentPage());
        $toSet = [];

        if ($events->isEmpty()) {
            return [];
        }

        if (!$this->client->exists($this->listKey)) {
            $this->client->rpush($this->listKey, range(0, 149));
        }

        foreach ($events as $event) {
            $key = $this->idKey . $event->id;
            $toSet[$key] = serialize($event);
            $this->client->lset($this->listKey, $start++, $key);
        }

        $result = $this->client->hmset($this->hsetKey, $toSet);
        $this->client->expire($this->hsetKey, $this->ttl);
        $this->client->expire($this->listKey, $this->ttl);

        return $result;
    }
}