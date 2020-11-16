<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class GetRepeatConfirmationTest extends BaseTestCase
{
    public function test_GetRepeatConfirmation_RedirectsUserBack()
    {
        $user = $this->createUser();
        $this->get('events');
        $response = $this->actingAs($user)->followingRedirects()->get('repeat-confirmation');

        $response->assertStatus(200);
    }
}