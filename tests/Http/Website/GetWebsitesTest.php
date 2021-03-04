<?php

namespace Tests\Http\Website;

use Tests\BaseTestCase;

final class GetWebsitesTest extends BaseTestCase
{
    public function test_GetWebsites_WithValidSearchParameters_ShowsWebsitesAndTotal()
    {
        $response = $this->get('websites/approved');
        $response->assertStatus(200);
        $response->assertSee('Pending (2)');
        $response->assertSee('Approved (5)');
        $response->assertSee('Rejected (5)');
    }

    public function test_APIGetWebsites_WithValidSearchParameters_ShowsWebsitesAndTotal()
    {
        $response = $this->get('api/websites/approved');
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'payload.websites.data');
        $this->assertEquals([
            'pending' => 2,
            'approved' => 5,
            'rejected' => 5,
        ], $response->json('payload.total'));
    }
}