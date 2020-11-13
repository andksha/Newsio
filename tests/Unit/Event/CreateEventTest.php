<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Contract\ApplicationException;
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

    /**
     * @throws ApplicationException
     */
    public function test_CreateEvent_WithValidInput_CreatesEvent()
    {
        $event = $this->uc->create(new CreateEventBoundary(
        'test event', ['test_tag', 'test_tag2'],
            ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef'],
        2, 2
        ));

        $this->assertEquals([
            'test event',
            'test_tag2',
            'https://biz.censor.net/event/ewfwef',
            2,
            2
        ], [
            $event->title,
            $event->tags->last()->name,
            $event->links->last()->content,
            $event->category->id,
            $event->user_id
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function test_CreateEvent_WithAlreadyExistingTitle_ThrowsAlreadyExistsException()
    {
        $this->uc->create(new CreateEventBoundary(
            'test event', ['test_tag', 'test_tag2'],
            ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef'],
            2, 2
        ));

        try {
            $this->uc->create(new CreateEventBoundary(
                'test event', ['test_tag', 'test_tag2'],
                ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef'],
                2, 2
            ));
        } catch (AlreadyExistsException $e) {
            $this->assertEquals($e->getMessage(), 'Event with title \'test event\' already exists');
        }
    }

    /**
     * @throws ApplicationException
     */
    public function test_CreateEvent_WithAlreadyExistingLink_ThrowsAlreadyExistsException()
    {
        $this->uc->create(new CreateEventBoundary(
            'test event', ['test_tag', 'test_tag2'],
            ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef'],
            2, 2
        ));

        try {
            $this->uc->create(new CreateEventBoundary(
                'test event2', ['test_tag', 'test_tag2'],
                ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef'],
                2, 2
            ));
        } catch (AlreadyExistsException $e) {
            $this->assertEquals($e->getMessage(), 'Link https://www.radiosvoboda.org/event/ergerg%435%324r already exists in this event');
        }
    }
}