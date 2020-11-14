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

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_GetEvents_WithNoQuery_ReturnsEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary([]));

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

        $events = $this->uc->getEvents(new GetEventsBoundary(['search' => $query]));

        $this->assertTrue($events->total() === 6 || $events->total() === 5);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_GetEvents_WithTagQuery_ReturnsOnlyRequestedEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary(['tag' => 'test']));

        $this->assertTrue($events->total() === 1);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_GetEvents_WithRemovedQuery_ReturnsOnlyRemovedEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary(['removed' => 'removed']));

        foreach ($events->items() as $item) {
            if ($item['deleted_at'] === null) {
                $this->fail('Non-removed event was returned');
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_GetEvents_WithAllSearchParameters_ReturnsEvents()
    {
        $events = $this->uc->getEvents(new GetEventsBoundary(['search' => 'fgdsa', 'tag' => 'tag1']));

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
