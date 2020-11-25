<?php

namespace Newsio\Repository;

use Newsio\Boundary\UseCase\GetEventsBoundary;


final class EventRepositoryFactory
{
    public function makeEventRepository(GetEventsBoundary $getEventsBoundary)
    {
        if ($getEventsBoundary->getRemoved() === 'removed') {
            return new RemovedEventRepository();
        }

        return new PublishedEventRepository();
    }
}