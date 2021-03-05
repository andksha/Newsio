<?php

namespace Tests\Http\Auth;

use Tests\BaseTestCase;

final class GetConfirmationTest extends BaseTestCase
{
    public function test_GetConfirmation_WithValidToken_ConfirmsEmail()
    {
        $this->createUser();
        $emailConfirmation = $this->createEmailConfirmation();
        $response = $this->followingRedirects()->get('confirmation?token=' . $emailConfirmation->token);

        $response->assertStatus(200);
    }

    public function test_APIGetConfirmation_WithValidToken_ConfirmsEmail()
    {
        $this->createUser();
        $emailConfirmation = $this->createEmailConfirmation();
        $response = $this->get('api/confirmation?token=' . $emailConfirmation->token);

        $response->assertStatus(200);
    }
}