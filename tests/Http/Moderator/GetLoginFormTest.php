<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class GetLoginFormTest extends BaseTestCase
{
    public function test_GetLoginForm_ShowsLoginForm()
    {
        $response = $this->get('moderator/login');
        $response->assertStatus(200);
    }
}