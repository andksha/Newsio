<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Tests\BaseTestCase;

final class GetModeratorsTest extends BaseTestCase
{
    public function test_GetModerators_ShowsModerators()
    {
        $admin = Admin::query()->first();

        $response = $this->actingAs($admin, 'admin')->get('admin/moderators');
        $response->assertStatus(200);
        $response->assertSee('Add moderator');
    }

    public function test_APIGetModerators_ShowsModerators()
    {
        $admin = Admin::query()->first();

        $response = $this->actingAs($admin, 'api_admin')
            ->get('api/admin/moderators');
        $response->assertStatus(200);
        $response->assertJsonStructure(['payload' => [
            'moderators'
        ]]);
    }
}