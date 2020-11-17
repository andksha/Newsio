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
}