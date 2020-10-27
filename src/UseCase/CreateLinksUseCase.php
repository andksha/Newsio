<?php

namespace Newsio\UseCase;

use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Model\Link;

class CreateLinksUseCase
{
    /**
     * @param LinksBoundary $linksBoundary
     * @throws AlreadyExistsException
     */
    public function checkLinks(LinksBoundary $linksBoundary)
    {
        $links = Link::query();

        foreach ($linksBoundary->getValues() as $value) {
            $links->orWhere('content', 'like', '%' . $value . '%');
        }

        if ($link = $links->first()) {
            throw new AlreadyExistsException('Link ' . $link->content . ' already exists', $link->event);
        }
    }

    public function createLinks(int $eventId, LinksBoundary $linksBoundary): bool
    {
        return Link::query()->insert(array_map(function ($value) use ($eventId) {
            return ['event_id' => $eventId, 'content' => $value];
        }, $linksBoundary->getValues()));
    }
}
