<?php

namespace Tests\Unit\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Website;
use Newsio\UseCase\Admin\RejectWebsiteUseCase;
use Tests\BaseTestCase;

class RejectWebsiteTest extends BaseTestCase
{
    private RejectWebsiteUseCase $uc;
    private Website $website;

    protected function setUp(): void
    {
        $this->uc = new RejectWebsiteUseCase();
        parent::setUp();
    }

    public function createWebsite()
    {
        $this->website = new Website();
        $this->website->domain = 'https://approve.website';
        $this->website->save();
        $this->website->refresh();
    }

    public function test_RejectWebsite_WithValidIdAndReason_RejectsWebsite()
    {
        $this->createWebsite();
        $website = $this->uc->reject(new IdBoundary($this->website->id), new StringBoundary('test_reason'));

        $this->assertTrue($website->domain === 'https://approve.website'
            && $website->approved === false
            && $website->reason === 'test_reason'
        );
    }

    public function test_RejectWebsite_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->reject(new IdBoundary(1000), new StringBoundary('test_reason'));
    }
}