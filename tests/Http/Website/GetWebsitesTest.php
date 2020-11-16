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
}