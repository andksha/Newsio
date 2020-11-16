<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class GetEventsTest extends BaseTestCase
{
    public function test_GetEvents_WithValidSearchParameters_ShowsEventsAndTags()
    {
        $this->createEvent('Get events test');
        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Get events test');
        $response->assertSee('Popular tags');
    }

    public function test_GetEvents_WithInvalidSearchParameters_ShowsError()
    {
        $this->createEvent('Get events test');
        $response = $this->get('/events?search[0]=test');
        $response->assertStatus(200);
        $response->assertDontSee('Save');
        $response->assertSee('Value must be empty or a string');
    }

    public function test_GetEvents_WithRemovedParameter_ReturnsRemovedEvents()
    {
        $event = $this->createEvent('Get events test');
        $event->remove('test-reason');

        $response = $this->get('/events/removed');
        $response->assertStatus(200);
        $response->assertSee('test-reason');
        $response->assertSee('X');
    }
}