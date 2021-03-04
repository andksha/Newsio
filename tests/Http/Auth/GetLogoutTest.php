<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class GetLogoutTest extends BaseTestCase
{
    public function test_GetLogout_LogsUserOutAndRedirectsBack()
    {
        $user = $this->createUser();
        $this->get('events');
        $response = $this->actingAs($user)->followingRedirects()->get('logout');
        $response->assertStatus(200);
        $response->assertSee('Register');
        $response->assertSee('Login');
    }

    public function test_APIGetLogout_LogsUserOutAndRedirectsBack()
    {
        $response = $this->withBearerToken()->get('api/logout');
        $response->dump();
        $response->assertStatus(200);
    }
}