<?php

namespace Newsio\UseCase\Moderator;

use App\Event\EventRemovedEvent;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Repository\PublishedEventRepository;

class RemoveEventUseCase
{
    private PublishedEventRepository $publishedEventRepository;

    public function __construct(PublishedEventRepository $publishedEventRepository)
    {
        $this->publishedEventRepository = $publishedEventRepository;
    }

    /**
     * @param IdBoundary $id
     * @param StringBoundary $reason
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Event|Event[]|null
     * @throws ModelNotFoundException
     */
    public function remove(IdBoundary $id, StringBoundary $reason)
    {
        if (!$event = Event::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->remove($reason->getValue())->removeLinks();
        $event->refresh()->load(Event::DEFAULT_RELATIONS);

        $this->publishedEventRepository->removeEvent($event);
        EventRemovedEvent::dispatch($event);

        return $event;
    }
}