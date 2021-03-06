<?php

namespace Tests\Http\Admin;

use App\Model\Admin;
use Tests\BaseTestCase;

final class PostLoginTest extends BaseTestCase
{
    public function test_PostLogin_WithValidInput_RedirectsToEvents()
    {
        $admin = Admin::query()->first();
        $response = $this->followingRedirects()->post('admin/login', [
            'email' => $admin->email,
            'password' => 'test1234'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Pending');
    }

    public function test_APIPostLogin_WithValidInput_RedirectsToEvents()
    {
        $admin = Admin::query()->first();
        $response = $this->followingRedirects()->post('api/admin/login', [
            'email' => $admin->email,
            'password' => 'test1234'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['payload' => [
            'token',
            'type',
            'expires_in'
        ]]);
    }
}