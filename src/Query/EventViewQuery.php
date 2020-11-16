<?php

namespace Newsio\Query;

use Illuminate\Database\Eloquent\Builder;
use Newsio\Model\EventView;

final class EventViewQuery
{
    private Builder $query;

    public static function query(): self
    {
        $event = new self();
        $event->query = EventView::query();

        return $event;
    }

    public function findUserEventViews(int $eventId, string $userIdentifier)
    {
        $this->query->where('event_id', $eventId)
            ->where('user_identifier', $userIdentifier);
        return $this;
    }

    public function orderByDesc(string $orderby)
    {
        $this->query->orderByDesc($orderby);
        return $this;
    }

    public function first()
    {
        return $this->query->first();
    }
}