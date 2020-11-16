<?php

namespace Tests\Unit\Moderator;

use Illuminate\Support\Str;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\UseCase\Moderator\RemoveLinkUseCase;
use Tests\BaseTestCase;

class RemoveLinkTest extends BaseTestCase
{
    private RemoveLinkUseCase $uc;
    private Event $event;
    private Link $link;

    protected function setUp(): void
    {
        $this->uc = new RemoveLinkUseCase();
        parent::setUp();
    }

    public function createEvent(bool $moreThanOneLink = true)
    {
        $this->event = new Event();
        $this->event->fill([
            'title' => 'to_be_removed',
            'user_id' => 2,
            'category_id' => 6
        ]);
        $this->event->save();
        $this->event->refresh();

        $this->link = new Link();
        $this->link->event_id = $this->event->id;
        $this->link->content = Str::random(32);
        $this->link->save();
        $this->link->refresh();

        if ($moreThanOneLink) {
            Link::query()->insert([
                [
                    'event_id' => $this->event->id,
                    'content' => Str::random(32)
                ]
            ]);
        }
    }

    /**
     * @throws ApplicationException
     */
    public function test_RemoveLink_WithValidIdAndReason_RemovesLink()
    {
        $this->createEvent();
        $link = $this->uc->remove(new IdBoundary($this->link->id), new StringBoundary('test_reason'));

        $this->assertTrue($link->reason === 'test_reason' && $link->deleted_at !== null);
    }

    /**
     * @throws ApplicationException
     */
    public function test_RemoveLink_LinkIsTheLastApprovedLinkWithValidIdAndReason_RemovesLinkAndEvent()
    {
        $this->createEvent(false);
        $link = $this->uc->remove(new IdBoundary($this->link->id), new StringBoundary('test_reason'));

        $this->assertTrue($link->reason === 'test_reason'
            && $link->deleted_at !== null
            && $link->event->reason === 'test_reason'
            && $link->event->deleted_at !== null
        );
    }

    /**
     * @throws ApplicationException
     */
    public function test_RemoveLink_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->remove(new IdBoundary(1000), new StringBoundary('test_reason'));
    }
}