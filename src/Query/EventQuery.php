<?php

namespace Newsio\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Newsio\Boundary\UseCase\GetEventsBoundary;
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

    public function whereUserSaved(?int $userId)
    {
        if ($userId) {
            $this->query->whereHas('userSaved', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }

        return $this;
    }

    public function withUserSaved(?int $userId)
    {
        if ($userId) {
            $this->query->with(['userSaved' => function (HasOne $query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }

        return $this;
    }

    public function user(?int $userId): self
    {
        if ($userId) {
            $this->query->where('user_id', $userId);
        }

        return $this;
    }

    public function frequentFields(GetEventsBoundary $boundary)
    {
        return $this->category($boundary->getCategory())
            ->removed($boundary->getRemoved())
            ->search($boundary->getSearch())
            ->tag($boundary->getTag());
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
            $this->query->where(function (Builder $query) use ($search) {
                $query->orWhereRaw(DB::raw('LOWER(title) LIKE ?'), ['%' . strtolower($search) . '%'])
                    ->orWhereHas('tags', function (Builder $query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('links', function (Builder $query) use ($search) {
                        $query->where('content', 'like', '%' . $search . '%');
                    });
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

    public function defaultOrder()
    {
        $this->query->orderByDesc('updated_at');

        return $this;
    }

    public function orderByDesc(string $orderBy)
    {
        $this->query->orderByDesc($orderBy);
        return $this;
    }

    public function with(array $relations)
    {
        $this->query->with($relations);
        return $this;
    }

    public function paginate(int $perPage)
    {
        return $this->query->paginate($perPage);
    }

    public function offset(int $offset)
    {
        return $this->query->offset($offset);
    }

    public function limit(int $limit)
    {
        return $this->query->limit($limit);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get()
    {
        return $this->query->get();
    }

    public function count(): int
    {
        return $this->query->count();
    }
}