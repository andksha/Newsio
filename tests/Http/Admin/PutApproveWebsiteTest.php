<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Newsio\Model\Website;
use Tests\BaseTestCase;

final class PutApproveWebsiteTest extends BaseTestCase
{
    public function test_PutApproveWebsite_WithValidInput_ReturnsWebsite()
    {
        $admin = Admin::query()->first();
        $website = Website::query()->create(['domain' => 'test@web.site']);
        $response = $this->actingAs($admin, 'admin')->put('admin/website', [
            'website_id' => $website->id
        ]);

        $response->assertJsonStructure(['website' => []]);
    }

    public function test_APIPutApproveWebsite_WithValidInput_ReturnsWebsite()
    {
        $admin = Admin::query()->first();
        $website = Website::query()->create(['domain' => 'test@web.site']);
        $response = $this->actingAs($admin, 'api_admin')->put('api/admin/website', [
            'website_id' => $website->id
        ]);

        $response->assertJsonStructure(['payload' => [
                'website'
            ]
        ]);
    }
}