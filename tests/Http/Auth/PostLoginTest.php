<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class PostLoginTest extends BaseTestCase
{
    public function test_PostLogin_WithValidInput_ReturnsSuccess()
    {
        $user = $this->createUser();
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'test1234'
        ]);

        $response->assertJson(['success' => true]);
    }

    public function test_APIPostLogin_WithValidInput_ReturnsSuccess()
    {
        $user = $this->createUser();
        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => 'test1234'
        ]);

        $response->assertJsonStructure(['payload' => [
            'token',
            'type',
            'expires_in'
        ]]);
    }
}