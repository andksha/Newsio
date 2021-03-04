<?php

namespace Tests\Http\Auth;

use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class PostPasswordResetTest extends BaseTestCase
{
    public function test_PostPasswordReset_WithValidToken_RedirectsToEvents()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset($user->email);
        $data = [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'token' => $passwordReset->token
        ];

        $response = $this->followingRedirects()->post('password/reset', $data);
        $response->assertStatus(200);
    }

    public function test_APIPostPasswordReset_WithValidToken_RedirectsToEvents()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset($user->email);
        $data = [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'token' => $passwordReset->token
        ];

        $response = $this->post('api/password/reset', $data);
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}