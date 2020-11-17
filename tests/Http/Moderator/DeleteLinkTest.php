<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class DeleteLinkTest extends BaseTestCase
{
    public function test_DeleteLink_WithValidInput_ReturnsLink()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent();
        $link = $this->createLink($event->id);

        $response = $this->actingAs($moderator, 'moderator')->delete('moderator/link', [
            'link_id' => $link->id,
            'reason' => 'test_reason'
        ]);

        $response->assertJsonStructure(['link' => []]);
    }
}