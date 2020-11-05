<?php

namespace Tests\Unit\Website;

use Newsio\Boundary\NullableStringBoundary;
use Newsio\UseCase\Website\GetWebsitesUseCase;
use Tests\BaseTestCase;

class GetWebsitesTest extends BaseTestCase
{
    private GetWebsitesUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new GetWebsitesUseCase();
    }

    public function test_GetWebsites_WithNoSearchParameters_ReturnsApprovedWebsites()
    {
        $websites = $this->uc->getWebsites(null, 20, new NullableStringBoundary(null));

        foreach ($websites as $website) {
            if ($website->approved !== '0') {
                $this->fail('Approved website');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetWebsites_WithApprovedStatus_ReturnsApprovedWebsites()
    {
        $websites = $this->uc->getWebsites('approved', 20, new NullableStringBoundary(null));

        foreach ($websites as $website) {
            if ($website->approved !== '1') {
                $this->fail('Not approved website');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetWebsites_WithPendingStatus_ReturnsPendingWebsites()
    {
        $websites = $this->uc->getWebsites('pending', 20, new NullableStringBoundary(null));

        foreach ($websites as $website) {
            if ($website->approved !== null) {
                $this->fail('Not pending website');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetWebsites_WithApprovedStatusAndSearch_ReturnsApprovedSearchedWebsite()
    {
        $websites = $this->uc->getWebsites('approved', 20, new NullableStringBoundary('censor'));

        foreach ($websites as $website) {
            if ($website->domain !== 'https://biz.censor.net/') {
                $this->fail('Not searched website');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetTotal_ReturnsTotalArray()
    {
        $total = $this->uc->getTotal();

        $this->assertEquals([
            'pending' => 2,
            'approved' => 5,
            'rejected' => 5
        ], $total);
    }
}