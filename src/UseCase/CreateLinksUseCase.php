<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Model\Link;

class CreateLinksUseCase
{
    /**
     * @param LinksBoundary $linksBoundary
     * @return CreateLinksUseCase
     * @throws AlreadyExistsException
     */
    public function checkLinks(LinksBoundary $linksBoundary)
    {
        $links = Link::query();

        foreach ($linksBoundary->getValues() as $value) {
            $links->orWhere('content', 'like', '%' . $value . '%');
        }

        if ($link = $links->first()) {
            throw new AlreadyExistsException('Link ' . $link->content . ' already exists in this event', [
                'event' => $link->event
            ]);
        }

        return $this;
    }

    public function createLinks(IdBoundary $eventId, LinksBoundary $linksBoundary): bool
    {
        return Link::query()->insert(array_map(function ($value) use ($eventId) {
            return ['event_id' => $eventId->getValue(), 'content' => $value];
        }, $linksBoundary->getValues()));
    }
}
