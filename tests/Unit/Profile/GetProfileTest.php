<?php

namespace Tests\Unit\Profile;

use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Profile\GetProfileUseCase;
use Tests\BaseTestCase;

final class GetProfileTest extends BaseTestCase
{
    private GetProfileUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new GetProfileUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function test_GetProfile_WithoutSavedParameter_ReturnsUsersEvents()
    {
        $events = $this->uc->getProfile(new GetEventsBoundary(['user_id' => 2]));

        foreach ($events as $event) {
            if ((int)$event->user_id !== 2) {
                $this->fail('User didn\'t create this event');
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @throws ApplicationException
     */
    public function test_GetProfile_WithSavedParameter_ReturnsUsersSavedEvents()
    {
        $events = $this->uc->getProfile(new GetEventsBoundary(['user_id' => 2, 'saved' => 'saved']));

        foreach ($events as $event) {
            if ((int)$event->userSaved->user_id !== 2) {
                $this->fail('User didn\'t save this event');
            }
        }

        $this->assertTrue(true);
    }
}