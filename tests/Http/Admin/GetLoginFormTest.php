<?php

namespace Tests\Http\Admin;

use Tests\BaseTestCase;

final class GetLoginFormTest extends BaseTestCase
{
    public function test_GetLoginForm_ShowsLoginForm()
    {
        $response = $this->get('admin/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }
}