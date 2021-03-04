<?php

namespace Tests\Http\Auth;

use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class PostPasswordTest extends BaseTestCase
{
    public function test_PostPassword_ReturnsSuccess()
    {
        $user = $this->createUser();
        $response = $this->post('password', ['email' => $user->email]);

        $response->assertJson(['success' => true]);
    }

    public function test_APIPostPassword_ReturnsSuccess()
    {
        $user = $this->createUser();
        $response = $this->post('api/password', ['email' => $user->email]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}