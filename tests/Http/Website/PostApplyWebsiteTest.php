<?php

namespace Tests\Http\Website;

use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class PostApplyWebsiteTest extends BaseTestCase
{
    public function test_PostApplyWebsite_WithValidDomain_RedirectsToPendingWebsites()
    {
        auth()->setUser($this->createUser()->verify());
        $response = $this->followingRedirects()->post('website', ['domain' => 'https://test.test']);
        $response->assertStatus(200);
    }

    public function test_APIPostApplyWebsite_WithValidDomain_RedirectsToPendingWebsites()
    {
        auth('api')->setUser($this->createUser()->verify());
        $response = $this->post('api/website', ['domain' => 'https://test.test']);
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}