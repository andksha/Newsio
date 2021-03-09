<?php

namespace Newsio\UseCase\Moderator;

use App\Event\LinkRestoredEvent;
use Carbon\Carbon;
use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Cache\PublishedEventCache;
use Newsio\Model\Cache\RemovedEventCache;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\Repository\RemovedEventRepository;

class RestoreLinkUseCase
{
    private RemovedEventRepository $removedEventRepository;

    public function __construct(RemovedEventRepository $removedEventRepository)
    {
        $this->removedEventRepository = $removedEventRepository;
    }

    /**
     * @param IdBoundary $id
     * @return \Illuminate\Database\Query\Builder|mixed|Link
     * @throws ModelNotFoundException
     */
    public function restore(IdBoundary $id)
    {
        if (!$link = Link::query()->onlyTrashed()->find($id->getValue())) {
            throw new ModelNotFoundException('Link');
        }

        $link->restore();
        $link->event->updated_at = Carbon::now();
        LinkRestoredEvent::dispatch($link->event);

        if ($link->event->trashed()) {
            $eventCache = new RemovedEventCache();
        } else {
            $eventCache = new PublishedEventCache();
        }

        $eventCache->addOrUpdateEvent($link->event->load(Event::DEFAULT_RELATIONS));

        return $link;
    }
}