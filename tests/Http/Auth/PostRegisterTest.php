<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class PostRegisterTest extends BaseTestCase
{
    public function test_PostRegister_WithValidInput_ReturnsUser()
    {
        $response = $this->post('register', [
            'email' => 'andrewafk2000@gmail.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
        ]);

        $response->assertJsonStructure(['user' => []]);
    }
}