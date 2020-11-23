<?php

namespace Newsio\Repository;

use Newsio\Boundary\UseCase\GetEventsBoundary;


final class EventRepositoryFactory
{
    public function makeEventRepository(GetEventsBoundary $boundary)
    {
        if ($boundary->getRemoved() === 'removed') {
            return new RemovedEventRepository();
        }

        return new PublishedEventRepository();
    }
}