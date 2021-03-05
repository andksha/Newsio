<?php

namespace Tests\Http\Moderator;

use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class PostLoginTest extends BaseTestCase
{
    public function test_PostLogin_WithValidInput_RedirectsToEvents()
    {
        $moderator = $this->createModerator()->verify();
        $response = $this->followingRedirects()->post('moderator/login', [
            'email' => $moderator->email,
            'password' => 'test1234'
        ]);

        $response->assertSee('published');
        $response->assertStatus(200);
    }

    public function test_APIPostLogin_WithValidInput_RedirectsToEvents()
    {
        $moderator = $this->createModerator()->verify();
        $response = $this->followingRedirects()->post('api/moderator/login', [
            'email' => $moderator->email,
            'password' => 'test1234'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['payload' => [
            'token',
            'type',
            'expires_in'
        ]]);
    }
}