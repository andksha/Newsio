<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class PostCreateModeratorTest extends BaseTestCase
{
    public function test_PostCreateModerator_WithValidInput_RedirectsToModerators()
    {
        $admin = Admin::query()->first();
        $response = $this->followingRedirects()->actingAs($admin, 'admin')->post('admin/moderator', [
           'email' => 'test@modera.tor'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Add moderator');
    }

    public function test_APIPostCreateModerator_WithValidInput_RedirectsToModerators()
    {
        $admin = Admin::query()->first();
        $response = $this->actingAs($admin, 'api_admin')
            ->post('api/admin/moderator', [
                'email' => 'test@modera.tor'
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}