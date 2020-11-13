<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\TagPeriodBoundary;
use Newsio\UseCase\GetTagsUseCase;
use Tests\BaseTestCase;

final class GetTagsTest extends BaseTestCase
{
    private GetTagsUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new GetTagsUseCase();
        parent::setUp();
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