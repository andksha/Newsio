<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class PostSaveEventTest extends BaseTestCase
{
    public function test_PostSaveEvent_WithValidInput_ReturnsSuccess()
    {
        $user = $this->createUser()->verify();
        $event = $this->createEvent();

        $response = $this->actingAs($user)->post('event/save', ['event_id' => $event->id]);

        $response->assertJson(['success' => true]);
    }

    public function test_APIPostSaveEvent_WithValidInput_ReturnsSuccess()
    {
        $event = $this->createEvent();
        $response = $this->actingAs($this->createUser()->verify(), 'api')
            ->post('api/event/save', ['event_id' => $event->id]);

        $response->assertJson(['payload' => ['success' => true]]);
    }
}