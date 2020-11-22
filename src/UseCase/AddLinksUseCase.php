<?php

namespace Newsio\UseCase;

use Illuminate\Support\Collection;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Event;

class AddLinksUseCase
{
    private EventCacheRepository $eventCache;

    public function __construct()
    {
        $this->eventCache = new PublishedEventCache();
    }

    /**
     * @param IdBoundary $id
     * @param LinksBoundary $links
     * @return Collection
     * @throws ModelNotFoundException
     * @throws \Newsio\Exception\AlreadyExistsException
     * @throws \Newsio\Exception\BoundaryException
     * @throws \Newsio\Exception\InvalidWebsiteException
     */
    public function addLinks(IdBoundary $id, LinksBoundary $links)
    {
        $createLinksUseCase = new CreateLinksUseCase();

        if (!$event = Event::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $existingLinks = $event->links->pluck('content');
        $createLinksUseCase->checkLinks($links)->createLinks(new IdBoundary($event->id), $links);

        $event->load(Event::DEFAULT_RELATIONS);
        $this->eventCache->addOrUpdateEvent($event);

        return $event->links->pluck('content')->diff($existingLinks);
    }
}