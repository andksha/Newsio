<?php

namespace Newsio;

use Illuminate\Database\Eloquent\Builder;
use Newsio\Model\Event;

final class EventQuery
{
    private Builder $query;

    public static function query(): self
    {
        $event = new self();
        $event->query = Event::query();

        return $event;
    }

    public function withUserSaved(?int $userId)
    {
        return $this->query->when($userId, function ($query, $userId) {
            return $query->with(['userSaved' => function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            }]);
        });
    }

    public function user(?int $userId): self
    {
        if ($userId) {
            $this->query->where('user_id', $userId);
        }

        return $this;
    }

    public function category(?int $categoryId): self
    {
        if ($categoryId) {
            $this->query->where('category_id', $categoryId);
        }

        return $this;
    }

    public function search(?string $search): self
    {
        if ($search) {
            $this->query->orWhere('title', 'like', '%' . $search . '%')
                ->orWhereHas('tags', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('links', function (Builder $query) use ($search) {
                    $query->where('content', 'like', '%' . $search . '%');
                });
        }

        return $this;
    }

    public function tag(?string $tag): self
    {
        if ($tag) {
            $this->query->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('name', $tag);
            });
        }

        return $this;
    }

    public function removed(?string $removed)
    {
        if ($removed === 'removed') {
            $this->query->onlyTrashed();
        }
        
        return $this;
    }

    public function orderByDesc(string $orderBy)
    {
        $this->query->orderByDesc($orderBy);
        return $this;
    }

    public function paginate(int $perPage)
    {
        return $this->query->paginate($perPage);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get()
    {
        return $this->query->get();
    }
}