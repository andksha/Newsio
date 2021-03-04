<?php

namespace Tests\Http\Auth;

use Illuminate\Http\Response;
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

    public function test_APIGetRepeatConfirmation_RedirectsUserBack()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->get('api/repeat-confirmation');

        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}