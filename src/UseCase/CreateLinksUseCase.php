<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Exception\InvalidWebsiteException;
use Newsio\Model\Link;
use Newsio\Model\Website;

class CreateLinksUseCase
{
    /**
     * @param LinksBoundary $linksBoundary
     * @return CreateLinksUseCase
     * @throws AlreadyExistsException
     * @throws InvalidWebsiteException
     */
    public function checkLinks(LinksBoundary $linksBoundary)
    {
        $values = $linksBoundary->getValues();
        $approvedWebsites = Website::query()->approved()->pluck('domain')->toArray();
        $links = Link::query();

        for ($j = 0; $j < count($values); $j++) {
            $found = false;

            for ($i = 0; $i < count($approvedWebsites); $i++) {
                if (stripos($values[$j], $approvedWebsites[$i]) !== false) {
                    $found = true;
                }
            }

            if ($found === false) {
                throw new InvalidWebsiteException('Link ' . $values[$j] . ' leads to unverified website', []);
            }
        }

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
