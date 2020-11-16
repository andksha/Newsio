<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Tests\BaseTestCase;

final class GetLogoutTest extends BaseTestCase
{
    public function test_GetLogout_LogsAdminOutAndRedirectsToEventsRoute()
    {
        $admin = Admin::query()->first();
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('admin/logout');

        $response->assertStatus(200);
        $response->assertSee('published');
    }
}