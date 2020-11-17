<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Newsio\Model\Website;
use Tests\BaseTestCase;

final class DeleteRejectWebsite extends BaseTestCase
{
    public function test_DeleteRejectWebsite_WithValidInput_ReturnsWebsite()
    {
        $admin = Admin::query()->first();
        $website = Website::query()->create(['domain' => 'test@web.site']);
        $response = $this->actingAs($admin, 'admin')->delete('admin/website', [
            'website_id' => $website->id,
            'reason' => 'test_reason'
        ]);

        $response->assertJsonStructure(['website' => []]);
    }
}