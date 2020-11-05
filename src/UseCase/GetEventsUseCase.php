<?php

namespace Newsio\UseCase;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Newsio\Boundary\NullableIntBoundary;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Model\Event;

class GetEventsUseCase
{
    /**
     * @param NullableStringBoundary $search
     * @param NullableStringBoundary $tag
     * @param NullableStringBoundary $removed
     * @param NullableIntBoundary $category
     * @return LengthAwarePaginator
     */
    public function getEvents(
        NullableStringBoundary $search,
        NullableStringBoundary $tag,
        NullableStringBoundary $removed,
        NullableIntBoundary $category
    ): LengthAwarePaginator
    {
        $events = Event::query();

        if ($category->getValue()) {
            $events->where('category_id', $category->getValue());
        }

        if ($search->getValue()) {
            $events->orWhere('title', 'like', '%' . $search->getValue() . '%')
                ->orWhereHas('tags', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search->getValue() . '%');
                })->orWhereHas('links', function (Builder $query) use ($search) {
                    $query->where('content', 'like', '%' . $search->getValue() . '%');
                });
        }

        if ($tag->getValue()) {
            $events->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('name', 'like', '%' . $tag->getValue());
            });
        }

        $events = $removed->getValue() === 'removed'
            ? $events->onlyTrashed()->orderByDesc('updated_at')->paginate(15)
            : $events->orderByDesc('updated_at')->paginate(15);

        return $events;
    }
}