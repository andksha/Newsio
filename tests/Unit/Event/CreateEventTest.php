<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\UseCase\CreateEventUseCase;
use Tests\BaseTestCase;

class CreateEventTest extends BaseTestCase
{
    private CreateEventUseCase $uc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new CreateEventUseCase();
    }

    public function test_CreateEvent_WithValidInput_CreatesEvent()
    {
        $event = $this->uc->create(
            new TitleBoundary('test_event'),
            new TagsBoundary(['test_tag', 'test_tag2']),
            new LinksBoundary(['test_link', 'test_link2']),
            new CategoryBoundary(2)
        );

        $this->assertEquals([
            'test_event',
            'test_tag2',
            'test_link2',
            2
        ], [
            $event->title,
            $event->tags->last()->name,
            $event->links->last()->content,
            $event->category->id
        ]);
    }

    public function test_CreateEvent_WithAlreadyExistingTitle_ThrowsAlreadyExistsException()
    {
        $this->uc->create(
            new TitleBoundary('test_event'),
            new TagsBoundary(['test_tag', 'test_tag2']),
            new LinksBoundary(['test_link', 'test_link2']),
            new CategoryBoundary(2)
        );

        try {
            $this->uc->create(
                new TitleBoundary('test_event'),
                new TagsBoundary(['test_tag', 'test_tag2']),
                new LinksBoundary(['test_link', 'test_link2']),
                new CategoryBoundary(2)
            );
        } catch (AlreadyExistsException $e) {
            $this->assertEquals($e->getMessage(), 'Event with title \'test_event\' already exists');
        }
    }

    public function test_CreateEvent_WithAlreadyExistingLink_ThrowsAlreadyExistsException()
    {
        $this->uc->create(
            new TitleBoundary('test_event'),
            new TagsBoundary(['test_tag', 'test_tag2']),
            new LinksBoundary(['test_link', 'test_link2']),
            new CategoryBoundary(2)
        );

        try {
            $this->uc->create(
                new TitleBoundary('test_event2'),
                new TagsBoundary(['test_tag', 'test_tag2']),
                new LinksBoundary(['test_link', 'test_link2']),
                new CategoryBoundary(2)
            );
        } catch (AlreadyExistsException $e) {
            $this->assertEquals($e->getMessage(), 'Link test_link already exists in this event');
        }
    }
}