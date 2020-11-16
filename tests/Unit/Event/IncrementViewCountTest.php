<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\UseCase\IncrementViewCountUseCase;
use Tests\BaseTestCase;

final class IncrementViewCountTest extends BaseTestCase
{
    private IncrementViewCountUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new IncrementViewCountUseCase();
        parent::setUp();
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    private function executeUseCase(int $eventId, string $ip, string $userAgent): bool
    {
        return $this->uc->incrementViewCount(
            new IdBoundary($eventId),
            new UserIdentifierBoundary(
                new StringBoundary($ip),
                new StringBoundary($userAgent)
            ));
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_IncrementViewCount_WithValidInput_IncrementsViewCount()
    {
        $event = $this->createEvent();
        $result = $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $event->refresh();

        $this->assertEquals(1, $event->view_count);
        $this->assertTrue($result);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_IncrementViewCount_WithTheSameIPAndUserAgent_NotIncrementsViewCount()
    {
        $event = $this->createEvent();
        $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $result = $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $event->refresh();

        $this->assertEquals(1, $event->view_count);
        $this->assertFalse($result);

    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_IncrementViewCount_WithNonExistingEvent_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->executeUseCase(1000, 'testIP', 'testUserAgent');
    }
}