<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\CreateEventUseCase;
use Newsio\UseCase\SaveEventUseCase;
use Tests\BaseTestCase;

final class SaveEventTest extends BaseTestCase
{
    private SaveEventUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new SaveEventUseCase();
        parent::setUp();
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_SaveEvent_WithValidIds_SavesEvent()
    {
        $userEvent = $this->uc->save(new IdBoundary(2), new IdBoundary(3));

        $this->assertTrue($userEvent->event_id === 2 && $userEvent->user_id === 3);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_SaveEvent_WithNonExistingEvent_ThrowsModelNotFoundException()
    {
        try {
            $this->uc->save(new IdBoundary(1000), new IdBoundary(3));
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('Event not found', $e->getMessage());
        }
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_SaveEvent_WithNonExistingUser_ThrowsModelNotFoundException()
    {
        try {
            $this->uc->save(new IdBoundary(2), new IdBoundary(1000));
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('User not found', $e->getMessage());
        }
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_SaveEvent_WithAlreadySavedEvent_ThrowsAlreadyExistsException()
    {
        $this->expectException(AlreadyExistsException::class);
        $this->uc->save(new IdBoundary(2), new IdBoundary(2));
        $this->uc->save(new IdBoundary(2), new IdBoundary(2));
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_SaveEvent_WithUsersEvent_ThrowsInvalidOperationException()
    {
        $createEventUseCase = new CreateEventUseCase();
        $event = $createEventUseCase->create(new CreateEventBoundary(
            'test-save-event', [], ['https://biz.censor.net/event/ewfwef'], 2, 2));

        $this->expectException(InvalidOperationException::class);
        $this->uc->save(new IdBoundary($event->id), new IdBoundary(2));
    }
}