<?php

namespace Newsio\UseCase\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;

class RemoveEventUseCase
{
    /**
     * @param IdBoundary $id
     * @param StringBoundary $reason
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Event|Event[]|null
     * @throws ModelNotFoundException
     */
    public function remove(IdBoundary $id, StringBoundary $reason)
    {
        if (!$event = Event::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->remove($reason->getValue());

        return $event;
    }
}