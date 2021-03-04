<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class PostCreateEventTest extends BaseTestCase
{
    public function test_PostCreateTest_WithValidInput_ReturnsEvent()
    {
        $user = $this->createUser();
        $user->verify();
        $response = $this->actingAs($user)->post('event', [
            'title' => 'test55',
            'tags' => [],
            'links' => ['https://www.radiosvoboda.org/test'],
            'category' => 2,
        ]);

        $response->assertJsonStructure(['event' => []]);
    }

    public function test_APIPostCreateTest_WithValidInput_ReturnsEvent()
    {
        $response = $this->actingAs($this->createUser()->verify(), 'api')->post('api/event', [
            'title' => 'test55',
            'tags' => [],
            'links' => ['https://www.radiosvoboda.org/test'],
            'category' => 2,
        ]);

        $response->assertJsonStructure(['payload' => [
                'event' => []
            ]
        ]);
    }
}