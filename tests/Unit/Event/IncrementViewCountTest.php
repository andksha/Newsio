<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Lib\PRedis;
use Newsio\Model\Cache\EventViewCache;
use Newsio\Repository\EventViewRepository;
use Newsio\UseCase\IncrementViewCountUseCase;
use Tests\BaseTestCase;

final class IncrementViewCountTest extends BaseTestCase
{
    private IncrementViewCountUseCase $uc;
    private PRedis $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new PRedis();
        $this->uc = new IncrementViewCountUseCase(new EventViewRepository(new EventViewCache($this->client)));
    }

    /**
     * @param int $eventId
     * @param string $ip
     * @param string $userAgent
     * @return bool
     * @throws ModelNotFoundException
     * @throws \Newsio\Exception\BoundaryException
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
        $this->client->hset('events.hset', 'id.' . $event->id, $event);
        $result = $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $cachedEvent = $this->client->hget('events.hset', 'id.' . $event->id);

        $this->assertEquals(1, $cachedEvent->view_count);
        $this->assertTrue($result);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_IncrementViewCount_WithTheSameIPAndUserAgent_NotIncrementsViewCount()
    {
        $event = $this->createEvent();

        $this->client->hset('events.hset', 'id.' . $event->id, $event);
        $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $result = $this->executeUseCase($event->id, 'testIP', 'testUserAgent');
        $cachedEvent = $this->client->hget('events.hset', 'id.' . $event->id);

        $this->assertEquals(1, $cachedEvent->view_count);
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