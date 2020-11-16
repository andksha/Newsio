<?php

namespace Tests\Unit\Moderator;

use Illuminate\Support\Str;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\UseCase\Moderator\RemoveEventUseCase;
use Tests\BaseTestCase;

class RemoveEventTest extends BaseTestCase
{
    private RemoveEventUseCase $uc;
    private Event $event;

    protected function setUp(): void
    {
        $this->uc = new RemoveEventUseCase();

        parent::setUp();
    }

    public function createEvent()
    {
        $this->event = new Event();
        $this->event->fill([
            'title' => 'to_be_removed',
            'user_id' => 2,
            'category_id' => 6
        ]);
        $this->event->save();
        $this->event->refresh();

        Link::query()->insert([
            [
                'event_id' => $this->event->id,
                'content' => Str::random(32)
            ], [
                'event_id' => $this->event->id,
                'content' => Str::random(32)
            ]
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function test_RemoveEvent_WithValidIdAndReason_RemovesEventWithItsLinks()
    {
        $this->createEvent();

        $event = $this->uc->remove(new IdBoundary($this->event->id), new StringBoundary('test_reason'));

        $this->assertTrue($event->id === $this->event->id
            && $event->reason === 'test_reason'
            && $event->deleted_at !== null
        );

        foreach ($event->links as $link) {
            if ($link->reason !== 'Removed with event' && $link->deleted_at === null) {
                $this->fail('Non-deleted link');
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @throws ApplicationException
     */
    public function test_RemoveEvent_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->remove(new IdBoundary(1000), new StringBoundary('test_reason'));
    }
}