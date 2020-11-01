<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\NullableStringBoundary;
use Newsio\UseCase\GetEventsUseCase;
use Tests\BaseTestCase;

class GetEventsTest extends BaseTestCase
{
    private GetEventsUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new GetEventsUseCase();
    }

    public function test_GetEvents_WithNoQuery_ReturnsEvents()
    {
        $events = $this->uc->getEvents(
            new NullableStringBoundary(null),
            new NullableStringBoundary(null),
            new NullableStringBoundary(null)
        );

        foreach ($events->items() as $item) {
            if ($item['deleted_at'] !== null) {
                $this->fail('Removed event was returned');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetEvents_WithQuery_ReturnsOnlyRequestedEvents()
    {
        $query = 'test';

        $events = $this->uc->getEvents(
            new NullableStringBoundary($query),
            new NullableStringBoundary(null),
            new NullableStringBoundary(null)
        );

        $this->assertTrue($events->total() === 3);
    }

    public function test_GetEvents_WithTagQuery_ReturnsOnlyRequestedEvents()
    {
        $events = $this->uc->getEvents(
            new NullableStringBoundary(null),
            new NullableStringBoundary('test'),
            new NullableStringBoundary(null)
        );

        $this->assertTrue($events->total() === 1);
    }

    public function test_GetEvents_WithRemovedQuery_ReturnsOnlyRemovedEvents()
    {
        $events = $this->uc->getEvents(
            new NullableStringBoundary(null),
            new NullableStringBoundary(null),
            new NullableStringBoundary('removed')
        );

        foreach ($events->items() as $item) {
            if ($item['deleted_at'] === null) {
                $this->fail('Non-removed event was returned');
            }
        }

        $this->assertTrue(true);
    }
}
