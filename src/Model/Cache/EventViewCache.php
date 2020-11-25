<?php

namespace Newsio\Model\Cache;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Contract\RedisClient;
use Newsio\Lib\PRedis;

final class EventViewCache
{
    private RedisClient $client;
    public const MAX_CACHED = 100;
    private string $eventViewsKey;
    private string $eventViewCountKey;
    private string $idKey;

    public function __construct(PRedis $client)
    {
        $this->client = $client;
        $this->eventViewsKey = 'events.views';
        $this->eventViewCountKey = 'events.view.count';
        $this->idKey = 'id.';
    }

    public function getEventView(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier)
    {
        return $this->client->hget($this->eventViewsKey, $eventId->getValue() . $userIdentifier->getValue());
    }

    public function getEvent(IdBoundary $eventId)
    {
        return $this->client->hget('events.hset', 'id.' . $eventId->getValue());
    }

    public function createEventView(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier)
    {
        $field = $eventId->getValue() . $userIdentifier->getValue();
        $value = [
            'event_id' => $eventId->getValue(),
            'user_identifier' => $userIdentifier->getValue()
        ];

        $this->client->hset($this->eventViewsKey, $field, $value);
        $this->incrementViewCount($eventId);

        return $this->updateEvent($eventId);
    }

    private function incrementViewCount(IdBoundary $eventId)
    {
        return $this->client->hincrby($this->eventViewCountKey, $eventId->getValue(), 1);
    }

    private function updateEvent(IdBoundary $eventId)
    {
        $eventKey = $this->idKey . $eventId->getValue();
        $event = $this->client->hget('events.hset', $eventKey);

        if ($event) {
            $event->view_count += 1;
            $this->client->hset('events.hset', $eventKey, $event);
        }

        return $eventKey;
    }

    public function maxCachedReached(): bool
    {
        return $this->client->hlen($this->eventViewsKey) >= self::MAX_CACHED;
    }

    public function getEventViews()
    {
        return $this->client->hgetall($this->eventViewsKey);
    }

    public function getEventViewCounts()
    {
        return $this->client->hgetall($this->eventViewCountKey);
    }

    public function clearEventViews()
    {
        return $this->client->del([$this->eventViewsKey]);
    }

    public function clearEventViewCount()
    {
        return $this->client->del([$this->eventViewCountKey]);
    }
}