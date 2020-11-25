<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Repository\EventViewRepository;

final class IncrementViewCountUseCase
{
    private EventViewRepository $eventViewRepository;

    public function __construct(EventViewRepository $eventViewRepository)
    {
        $this->eventViewRepository = $eventViewRepository;
    }

    /**
     * @param IdBoundary $eventId
     * @param UserIdentifierBoundary $userIdentifier
     * @return bool
     * @throws ModelNotFoundException
     */
    public function incrementViewCount(IdBoundary $eventId, UserIdentifierBoundary $userIdentifier): bool
    {
        if ($this->eventViewRepository->getEventView($eventId, $userIdentifier)) {
            return false;
        }

        if (!$this->eventViewRepository->getEvent($eventId)) {
            throw new ModelNotFoundException('Event');
        }

        $this->eventViewRepository->createEventView($eventId, $userIdentifier);

        return true;
    }
}