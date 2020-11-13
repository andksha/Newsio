<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Model\Event;

class GetEventsUseCase
{
    /**
     * @param GetEventsBoundary $boundary
     * @return LengthAwarePaginator
     */
    public function getEvents(GetEventsBoundary $boundary): LengthAwarePaginator
    {
        $events = Event::query();

        if ($boundary->getCategory()) {
            $events->where('category_id', $boundary->getCategory());
        }

        if ($boundary->getSearch()) {
            $events->orWhere('title', 'like', '%' . $boundary->getSearch() . '%')
                ->orWhereHas('tags', function (Builder $query) use ($boundary) {
                    $query->where('name', 'like', '%' . $boundary->getSearch() . '%');
                })->orWhereHas('links', function (Builder $query) use ($boundary) {
                    $query->where('content', 'like', '%' . $boundary->getSearch() . '%');
                });
        }

        if ($boundary->getTag()) {
            $events->whereHas('tags', function (Builder $query) use ($boundary) {
                $query->where('name', $boundary->getTag());
            });
        }

        $events = $boundary->getRemoved() === 'removed'
            ? $events->onlyTrashed()->orderByDesc('updated_at')->paginate(15)
            : $events->orderByDesc('updated_at')->paginate(15);

        return $events;
    }
}