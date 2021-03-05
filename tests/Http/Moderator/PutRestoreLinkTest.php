<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class PutRestoreLinkTest extends BaseTestCase
{
    public function test_PutRestoreLink_WithValidInput_ReturnsLink()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent();
        $link = $this->createLink($event->id);
        $link->remove('test_reason');

        $response = $this->actingAs($moderator, 'moderator')->put('moderator/link', [
            'link_id' => $link->id,
        ]);

        $response->assertJsonStructure(['link' => []]);
    }

    public function test_APIPutRestoreLink_WithValidInput_ReturnsLink()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent();
        $link = $this->createLink($event->id);
        $link->remove('test_reason');

        $response = $this->actingAs($moderator, 'api_moderator')->put('api/moderator/link', [
            'link_id' => $link->id,
        ]);

        $response->assertJsonStructure(['payload' => [
            'link'
        ]
        ]);
    }
}