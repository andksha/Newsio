<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class PutRestoreEventTest extends BaseTestCase
{
    public function test_PutRestoreEvent_WithValidInput_ReturnsEvent()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent()->remove('test_reason');
        $this->createLink($event->id);

        $response = $this->actingAs($moderator, 'moderator')->put('moderator/event', [
            'event_id' => $event->id,
        ]);

        $response->assertJsonStructure(['event' => []]);
    }

    public function test_APIPutRestoreEvent_WithValidInput_ReturnsEvent()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent()->remove('test_reason');
        $this->createLink($event->id);

        $response = $this->actingAs($moderator, 'api_moderator')->put('api/moderator/event', [
            'event_id' => $event->id,
        ]);

        $response->assertJsonStructure(['payload' => [
                'event'
            ]
        ]);
    }
}