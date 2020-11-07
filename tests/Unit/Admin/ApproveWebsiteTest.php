<?php

namespace Tests\Unit\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Website;
use Newsio\UseCase\Admin\ApproveWebsiteUseCase;
use Tests\BaseTestCase;

class ApproveWebsiteTest extends BaseTestCase
{
    private ApproveWebsiteUseCase $uc;
    private Website $website;

    protected function setUp(): void
    {
        $this->uc = new ApproveWebsiteUseCase();
        parent::setUp();
    }

    public function createWebsite()
    {
        $this->website = new Website();
        $this->website->domain = 'https://approve.website';
        $this->website->save();
        $this->website->refresh();
    }

    public function test_ApproveWebsite_WithValidId_ApprovesWebsite()
    {
        $this->createWebsite();
        $website = $this->uc->approve(new IdBoundary($this->website->id));

        $this->assertTrue($website->domain === 'https://approve.website' && $website->approved === true);
    }

    public function test_ApproveWebsite_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->approve(new IdBoundary(1000));
    }
}