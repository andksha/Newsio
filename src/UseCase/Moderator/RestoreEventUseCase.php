<?php

namespace Newsio\UseCase\Moderator;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Repository\RemovedEventRepository;

class RestoreEventUseCase
{
    private RemovedEventRepository $removedEventRepository;

    public function __construct(RemovedEventRepository $removedEventRepository)
    {
        $this->removedEventRepository = $removedEventRepository;
    }

    /**
     * @param IdBoundary $id
     * @return \Illuminate\Database\Query\Builder|mixed|Event
     * @throws ModelNotFoundException
     * @throws \Newsio\Exception\InvalidOperationException
     */
    public function restore(IdBoundary $id)
    {
        if (!$event = Event::query()->onlyTrashed()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->restore();
        $event->refresh()->load(Event::DEFAULT_RELATIONS);

        $this->removedEventRepository->removeEvent($event);

        return $event;
    }
}