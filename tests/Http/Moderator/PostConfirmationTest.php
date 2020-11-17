<?php

namespace Tests\Http\Moderator;

use App\Model\ModeratorConfirmation;
use Illuminate\Support\Str;
use Tests\BaseTestCase;

final class PostConfirmationTest extends BaseTestCase
{
    public function test_PostConfirmation_WithValidInput_RedirectModeratorToLogin()
    {
        $moderator = $this->createModerator();
        $confirmation = ModeratorConfirmation::query()->create([
            'email' => $moderator->email,
            'token' => Str::random(32)
        ]);

        $response = $this->followingRedirects()->post('moderator/confirmation', [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'token' => $confirmation->token
        ]);

        $response->assertStatus(200);
        $response->assertSee('login');
    }
}