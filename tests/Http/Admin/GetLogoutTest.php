<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Illuminate\Http\Response;
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

    public function test_APIGetLogout_LogsAdminOutAndRedirectsToEventsRoute()
    {
        $admin = Admin::query()->first();
        $response = $this->withBearerToken('api_admin')
            ->actingAs($admin, 'api_admin')
            ->get('api/admin/logout');

        $response->assertStatus(Response::HTTP_OK);
    }
}