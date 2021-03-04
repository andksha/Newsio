<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class PostRefreshTokenTest extends BaseTestCase
{
    public function test_PostRefreshToken_WithValidInput_ReturnsSuccess()
    {
        $response = $this->withBearerToken()->post('api/refresh');

        $response->assertJsonStructure(['payload' => [
            'token',
            'type',
            'expires_in'
        ]]);
    }
}