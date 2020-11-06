<?php

namespace Newsio\UseCase\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;

class RestoreEventUseCase
{
    /**
     * @param IdBoundary $id
     * @return \Illuminate\Database\Query\Builder|mixed|Event
     * @throws ModelNotFoundException
     */
    public function restore(IdBoundary $id)
    {
        if (!$event = Event::query()->onlyTrashed()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->restore();

        return $event;
    }
}