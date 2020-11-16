<?php

namespace Tests\Http\Profile;

use Carbon\Carbon;
use Tests\BaseTestCase;

final class GetProfileTest extends BaseTestCase
{
    public function test_GetProfile_ShowsProfile()
    {
        $user = $this->createUser();
        $user->email_verified_at = Carbon::now();
        $user->save();

        $response = $this->actingAs($user)->get('profile');
        $response->assertStatus(200);
        $response->assertSee('My events');
        $response->assertSee('Saved events');
    }
}