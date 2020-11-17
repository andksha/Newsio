<?php

namespace Tests\Http\Moderator;

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
}