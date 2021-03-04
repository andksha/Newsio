<?php

namespace Tests\Http\Event;

use Illuminate\Http\Response;
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

    public function test_APIPostAddLinks_WithValidInput_ReturnsNewLinks()
    {
        $event = $this->createEvent();
        $response = $this->actingAs($this->createUser()->verify(), 'api')->post('/api/links', [
            'event_id' => $event->id,
            'links' => [
                'https://ru.krymr.com/test2'
            ]
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['payload' => [
                'new_links' => []
            ]
        ]);
    }
}