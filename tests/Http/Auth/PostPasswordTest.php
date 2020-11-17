<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class PostPasswordTest extends BaseTestCase
{
    public function test_PostPassword_ReturnsSuccess()
    {
        $user = $this->createUser();
        $response = $this->post('password', ['email' => $user->email]);

        $response->assertJson(['success' => true]);
    }
}