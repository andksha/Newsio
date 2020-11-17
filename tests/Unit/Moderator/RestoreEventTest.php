<?php

namespace Tests\Unit\Moderator;

use Illuminate\Support\Str;
use Newsio\Boundary\IdBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\UseCase\Moderator\RestoreEventUseCase;
use Tests\BaseTestCase;

class RestoreEventTest extends BaseTestCase
{
    private RestoreEventUseCase $uc;
    private Event $event;

    protected function setUp(): void
    {
        $this->uc = new RestoreEventUseCase();

        parent::setUp();
    }

    public function createDeletedEvent()
    {
        $this->event = new Event();
        $this->event->fill([
            'title' => 'to_be_removed',
            'user_id' => 2,
            'category_id' => 6
        ]);
        $this->event->reason = 'test_reason';
        $this->event->deleted_at = '2020-11-06 17:04:24';
        $this->event->save();
        $this->event->refresh();
    }

    private function createLinks(bool $restorable = true)
    {
        $link2 =  [
            'event_id' => $this->event->id,
            'content' => Str::random(32),
            'deleted_at' => $restorable === true ? null : '2020-11-06 17:04:24',
            'reason' => $restorable === true ? '' : 'Removed with event'
        ];

        Link::query()->insert([
            [
                'event_id' => $this->event->id,
                'content' => Str::random(32),
                'deleted_at' => '2020-11-06 17:04:24',
                'reason' => 'Removed with event'
            ], $link2
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function test_RestoreEvent_WithValidIdAndApprovedLinks_RestoresEvent()
    {
        $this->createDeletedEvent();
        $this->createLinks();
        $event = $this->uc->restore(new IdBoundary($this->event->id));

        $this->assertTrue($event->deleted_at === null && $event->reason === '');
    }

    /**
     * @throws ApplicationException
     */
    public function test_RestoreEvent_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->restore(new IdBoundary(1000));
    }

    /**
     * @throws ApplicationException
     */
    public function test_RestoreEvent_WithoutApprovedLinks_ThrowsInvalidOperationException()
    {
        $this->createDeletedEvent();
        $this->createLinks(false);
        $this->expectException(InvalidOperationException::class);
        $this->uc->restore(new IdBoundary($this->event->id));
    }
}