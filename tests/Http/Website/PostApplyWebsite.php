<?php

namespace Tests\Http\Website;

use Tests\BaseTestCase;

final class PostApplyWebsite extends BaseTestCase
{
    public function test_PostApplyWebsite_WithValidDomain_RedirectsToPendingWebsites()
    {
        $response = $this->followingRedirects()->post('website', ['domain' => 'test@test.test']);
        $response->assertStatus(200);
    }
}