<?php

namespace Tests\Http\Moderator;

use Tests\BaseTestCase;

final class DeleteEventTest extends BaseTestCase
{
    public function test_DeleteEvent_WithValidInput_ReturnsEvent()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent();
        $response = $this->actingAs($moderator, 'moderator')->delete('moderator/event', [
            'event_id' => $event->id,
            'reason' => 'test_reason'
        ]);

        $response->assertJsonStructure(['event' => []]);
    }

    public function test_APIDeleteEvent_WithValidInput_ReturnsEvent()
    {
        $moderator = $this->createModerator();
        $event = $this->createEvent();
        $response = $this->actingAs($moderator, 'api_moderator')->delete('api/moderator/event', [
            'event_id' => $event->id,
            'reason' => 'test_reason'
        ]);

        $response->assertJsonStructure(['payload' => [
                'event'
            ]
        ]);
    }
}