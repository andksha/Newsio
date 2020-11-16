<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class GetConfirmationFormTest extends BaseTestCase
{
    public function test_GetConfirmModeratorForm_ShowsConfirmModeratorForm()
    {
        $response = $this->get('moderator/confirmation');
        $response->assertStatus(200);
    }
}