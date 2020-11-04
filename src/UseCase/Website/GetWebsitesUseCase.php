<?php

namespace Newsio\UseCase\Website;

use Illuminate\Database\Eloquent\Builder;
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

    public function getTotal(): array
    {
        $total = DB::table('websites')->select('approved', DB::raw('count(*) as total'))
            ->groupBy('approved')
            ->get();

        return [
            'pending' => $total->where('approved', '===', null)->first()->total,
            'approved' => $total->where('approved', true)->first()->total,
            'rejected' => $total->where('approved', '===', false)->first()->total
        ];
    }
}