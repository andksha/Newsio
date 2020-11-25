<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Lib\PRedis;
use Newsio\UseCase\GetTagsUseCase;
use Tests\BaseTestCase;

final class GetTagsTest extends BaseTestCase
{
    private GetTagsUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new GetTagsUseCase(new PRedis());
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_GetTags_WithValidPeriod_ReturnsTags()
    {
        $tags = $this->uc->getPopularAndRareTags(new TagPeriodBoundary('month'));

        $this->assertTrue(isset($tags['popular']) && isset($tags['rare']));
    }
}