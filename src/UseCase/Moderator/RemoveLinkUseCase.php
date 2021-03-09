<?php

namespace Newsio\UseCase\Moderator;

use App\Event\EventRemovedEvent;
use App\Event\LinkRemovedEvent;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\Repository\RemovedEventRepository;

class RemoveLinkUseCase
{
    private RemovedEventRepository $removedEventRepository;

    public function __construct(RemovedEventRepository $removedEventRepository)
    {
        $this->removedEventRepository = $removedEventRepository;
    }

    /**
     * @param IdBoundary $id
     * @param StringBoundary $reason
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function remove(IdBoundary $id, StringBoundary $reason): Link
    {
        if (!$link = Link::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Link');
        }

        $link->remove($reason->getValue());

        if (!$link->event->trashed() && !$link->publishedLinksPresent()) {
            return $this->removeEvent($link, $reason);
        }

        $this->updateEvent($link);
        LinkRemovedEvent::dispatch($link->event);

        return $link;
    }

    private function removeEvent(Link $link, StringBoundary $reason)
    {
        $link->event->remove($reason->getValue())->refresh()->load(Event::DEFAULT_RELATIONS);
        $this->removedEventRepository->removeEvent($link->event);
        EventRemovedEvent::dispatch($link->event);

        return $link;
    }

    private function updateEvent(Link $link)
    {
        if ($link->event->trashed()) {
            $eventCache = new RemovedEventCache();
        } else {
            $eventCache = new PublishedEventCache();
        }

        $eventCache->addOrUpdateEvent($link->event->refresh()->load(Event::DEFAULT_RELATIONS));
    }
}