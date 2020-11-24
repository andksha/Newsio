<?php

namespace Newsio\UseCase;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Model\Cache\TagCache;
use Newsio\Model\EventTag;

final class GetTagsUseCase
{
    private TagCache $tagCache;

    public function __construct(TagCache $tagCache)
    {
        $this->tagCache = $tagCache;
    }

    /**
     * @param TagPeriodBoundary $period
     * @return array
     * @throws \Newsio\Exception\BoundaryException
     */
    public function getPopularAndRareTags(TagPeriodBoundary $period)
    {
        return $this->tagCache->hremember($period->getString(), function () use ($period) {
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