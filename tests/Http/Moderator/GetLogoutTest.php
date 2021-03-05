<?php

namespace Tests\Http\Moderator;

use App\Model\Moderator;
use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class GetLogoutTest extends BaseTestCase
{
    public function test_GetLogout_LogsModeratorOutAndRedirectsToEvents()
    {
        $moderator = Moderator::query()->create([
            'email' => 'test@modera.tor',
            'password' => 'test1234'
        ]);

        $response = $this->actingAs($moderator, 'moderator')->followingRedirects()->get('moderator/logout');
        $response->assertStatus(200);
        $response->assertSee('published');
    }

    public function test_APIGetLogout_LogsModeratorOutAndRedirectsToEvents()
    {
        $moderator = Moderator::query()->create([
            'email' => 'test@modera.tor',
            'password' => 'test1234'
        ]);

        $response = $this->withBearerToken('api_moderator')->actingAs($moderator, 'api_moderator')
            ->get('api/moderator/logout');
        $response->assertStatus(Response::HTTP_OK);
    }
}