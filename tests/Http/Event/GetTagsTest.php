<?php

namespace Tests\Http\Event;

use Tests\BaseTestCase;

final class GetTagsTest extends BaseTestCase
{
    public function test_GetTags_WithValidPeriod_ReturnsTags()
    {
        $response = $this->get('/tags?period=week');
        $response->assertStatus(200);
        $response->assertJsonStructure(['tags' => [
            'popular' => [],
            'rare' => []
        ]]);
    }

    public function test_GetTags_WithInvalidPeriod_ReturnsError()
    {
        $response = $this->get('/tags');
        $response->assertStatus(400);
        $response->assertJsonStructure(['error_message' => [], 'error_data' => []]);
    }
}