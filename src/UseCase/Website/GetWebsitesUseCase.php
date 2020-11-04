<?php

namespace Newsio\UseCase\Website;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Model\Website;

class GetWebsitesUseCase
{
    public function getWebsites($status, int $perPage, NullableStringBoundary $search)
    {
        return Website::query()->status($status)
            ->when($search->getValue() , function (Builder $query, $value) {
                return $query->where('domain', 'like', '%' . $value . '%');
            })->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getTotal(): Collection
    {
        return DB::table('websites')->select('approved', DB::raw('count(*) as total'))
            ->groupBy('approved')
            ->get();
    }
}