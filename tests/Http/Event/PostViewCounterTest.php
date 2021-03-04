<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class PostViewCounterTest extends BaseTestCase
{
    public function test_PostViewCounter_WithValidInput_ReturnsSuccess()
    {
        $event = $this->createEvent();
        $response = $this->post('view-counter', ['event_id' => $event->id]);

        $response->assertJson(['success' => true]);
    }

    public function test_APIPostViewCounter_WithValidInput_ReturnsSuccess()
    {
        $event = $this->createEvent();
        $response = $this->post('api/view-counter', ['event_id' => $event->id]);
        $response->assertJson(['payload' => ['success' => true]]);
    }
}