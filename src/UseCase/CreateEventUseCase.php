<?php

namespace Newsio\UseCase;

use App\Event\EventCreatedEvent;
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
     * @param CreateEventBoundary $createEventBoundary
     * @return Event
     * @throws AlreadyExistsException
     * @throws \Newsio\Exception\BoundaryException
     * @throws \Newsio\Exception\InvalidWebsiteException
     */
    public function create(CreateEventBoundary $createEventBoundary): Event
    {
        $this->checkTitle($createEventBoundary->getTitle());
        $this->createLinksUseCase->checkLinks($createEventBoundary->getLinks());

        $event = new Event();
        $event->fill([
            'title'       => $createEventBoundary->getTitle()->getValue(),
            'user_id'     => $createEventBoundary->getUserId(),
            'category_id' => $createEventBoundary->getCategory(),
        ]);
        $event->save();

        $this->createTagsUseCase->createTags($createEventBoundary->getTags())->createEventTags($event->id, $createEventBoundary->getTags());
        $this->createLinksUseCase->createLinks(new IdBoundary($event->id), $createEventBoundary->getLinks());

        $event->refresh()->load(['tags', 'links']);
        EventCreatedEvent::dispatch($event);
        $this->eventCache->addOrUpdateEvent($event);

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
