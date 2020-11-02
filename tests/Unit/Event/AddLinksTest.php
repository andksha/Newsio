<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Link;
use Newsio\UseCase\AddLinksUseCase;
use Tests\BaseTestCase;

class AddLinksTest extends BaseTestCase
{
    private AddLinksUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new AddLinksUseCase();
    }

    public function test_AddLinks_WithValidIdAndLinks_AddsLinks()
    {
        $this->uc->addLinks(new IdBoundary(2), new LinksBoundary(['testlink1', 'testlink2']));
        $links = Link::query()->whereIn('content', ['testlink1', 'testlink2'])->count();

        $this->assertEquals($links, 2);
    }

    public function test_AddLinks_WithNonExistingEvent_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->addLinks(new IdBoundary(1000), new LinksBoundary(['testlink1', 'testlink2']));
    }
}