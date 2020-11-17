<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class PostAddLinksTest extends BaseTestCase
{
    public function test_PostAddLinks_WithValidInput_ReturnsNewLinks()
    {
        $user = $this->createUser()->verify();
        $event = $this->createEvent();
        $response = $this->followingRedirects()->actingAs($user)->post('links', [
            'event_id' => $event->id,
            'links' => [
                'https://ru.krymr.com/test2'
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['new_links' => []]);
    }
}