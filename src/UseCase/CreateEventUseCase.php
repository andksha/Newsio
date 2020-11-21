<?php

namespace Newsio\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Contract\EventCacheRepository;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Event;

class CreateEventUseCase
{
    private CreateTagsUseCase $createTagsUseCase;
    private CreateLinksUseCase $createLinksUseCase;
    private EventCacheRepository $eventCache;

    public function __construct()
    {
        $this->createTagsUseCase = new CreateTagsUseCase();
        $this->createLinksUseCase = new CreateLinksUseCase();
        $this->eventCache = new PublishedEventCache();
    }

    /**
     * @param CreateEventBoundary $boundary
     * @return Event
     * @throws AlreadyExistsException
     * @throws \Newsio\Exception\BoundaryException
     * @throws \Newsio\Exception\InvalidWebsiteException
     */
    public function create(CreateEventBoundary $boundary): Event
    {
        $this->checkTitle($boundary->getTitle());
        $this->createLinksUseCase->checkLinks($boundary->getLinks());

        $event = new Event();
        $event->fill([
            'title'       => $boundary->getTitle()->getValue(),
            'user_id'     => $boundary->getUserId(),
            'category_id' => $boundary->getCategory(),
        ]);
        $event->save();

        $this->createTagsUseCase->createTags($boundary->getTags())->createEventTags($event->id, $boundary->getTags());
        $this->createLinksUseCase->createLinks(new IdBoundary($event->id), $boundary->getLinks());

        $event->load(['tags', 'links']);
//        $this->eventCache->addOrUpdateEvent($event);

        return $event;
    }

    /**
     * @param TitleBoundary $title
     * @return CreateEventUseCase
     * @throws AlreadyExistsException
     */
    public function checkTitle(TitleBoundary $title): CreateEventUseCase
    {
        if ($event = Event::query()->where('title', 'like', '%' . $title->getValue() . '%')->first()) {
            throw new AlreadyExistsException('Event with title \'' . $title->getValue() . '\' already exists', [
                'event' => $event
            ]);
        }

        return $this;
    }
}
