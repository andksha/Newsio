<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class GetPasswordResetFormTest extends BaseTestCase
{
    public function test_GetPasswordResetForm_ShowsPasswordResetForm()
    {
        $response = $this->get('password?token=test_token');
        $response->assertStatus(200);
        $response->assertSee('test_token');
    }
}