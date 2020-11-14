<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\UseCase\GetEventsBoundary;
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
        $events = $this->uc->getEvents(new GetEventsBoundary(null, null, null, null, null, null));

        foreach ($events->items() as $item) {
            if ($item['deleted_at'] !== null) {
                $this->fail('Removed event was returned');
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_GetEvents_WithQuery_ReturnsOnlyRequestedEvents()
    {
        $query = 'test';

        $events = $this->uc->getEvents(new GetEventsBoundary($query, null, null, null, null, null));

        $this->assertTrue($events->total() === 6 || $events->total() === 5);
    }

    public function test_GetEvents_WithTagQuery_ReturnsOnlyRequestedEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary(null, 'test', null, null, null, null));

        $this->assertTrue($events->total() === 1);
    }

    public function test_GetEvents_WithRemovedQuery_ReturnsOnlyRemovedEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary(null, null, 'removed', null, null, null));

        foreach ($events->items() as $item) {
            if ($item['deleted_at'] === null) {
                $this->fail('Non-removed event was returned');
            }
        }

        $this->assertTrue(true);
    }

    public function test_GetEvents_WithAllSearchParameters_ReturnsEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary('fgdsa', 'tag1', null, null, null, null));

        foreach ($events as $event) {
            if (
                $event->title !== 'sdfgdsasdv'
                || $event->deleted_at !== null
                || $event->tags->first()->name !== 'tag1'
            ) {
                $this->fail('Found incorrect event');
            }
        }

        $this->assertTrue(true);
    }

}
