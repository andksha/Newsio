<?php

namespace Tests\Unit\Website;

use Newsio\Boundary\DomainBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Model\Website;
use Newsio\UseCase\Website\ApplyWebsiteUseCase;
use Tests\BaseTestCase;

class ApplyWebsiteTest extends BaseTestCase
{
    private ApplyWebsiteUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new ApplyWebsiteUseCase();
    }

    public function test_ApplyWebsite_WithValidDomain_ReturnsNewWebsite()
    {
        $website = $this->uc->apply(new DomainBoundary('https://test.com'));

        $this->assertEquals('https://test.com', $website->domain);
    }

    public function test_ApplyWebsite_WithExistingDomain_ThrowsAlreadyExistingException()
    {
        Website::query()->create(['domain' => 'https://test.com']);
        $this->expectException(AlreadyExistsException::class);

        $this->uc->apply(new DomainBoundary('https://test.com'));
    }
}