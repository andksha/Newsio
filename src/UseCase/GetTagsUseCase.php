<?php

namespace Newsio\UseCase;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Model\EventTag;

final class GetTagsUseCase
{
    /**
     * @param TagPeriodBoundary $period
     * @return array
     * @throws \Newsio\Exception\BoundaryException
     */
    public function getPopularAndRareTags(TagPeriodBoundary $period)
    {
        return Cache::remember('tags_' . $period->getPeriod(), 60*60, function () use ($period) {
            return [
                'popular' => $this->popularTags($period->getValue()),
                'rare' => $this->rareTags($period->getValue())
            ];
        });

    }

    private function popularTags(Carbon $period): Collection
    {
        return EventTag::query()->select('tag_id', DB::raw('count(*) as total'))
            ->where('created_at', '>', $period)
            ->groupBy('tag_id')
            ->orderByDesc('total')
            ->limit(15)
            ->with('tag')
            ->get();
    }

    private function rareTags(Carbon $period): Collection
    {
        return EventTag::query()->select('tag_id', DB::raw('count(*) as total'))
            ->where('created_at', '>', $period)
            ->groupBy('tag_id')
            ->orderBy('total')
            ->limit(15)
            ->with('tag')
            ->get();
    }
}