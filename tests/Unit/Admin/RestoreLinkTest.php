<?php

namespace Tests\Unit\Admin;

use Illuminate\Support\Str;
use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Link;
use Newsio\UseCase\Moderator\RestoreLinkUseCase;
use Tests\BaseTestCase;

class RestoreLinkTest extends BaseTestCase
{
    private RestoreLinkUseCase $uc;
    private Link $link;

    protected function setUp(): void
    {
        $this->uc = new RestoreLinkUseCase();
        parent::setUp();
    }

    private function createLink()
    {
        $this->link = new Link();
        $this->link->deleted_at = '2020-11-06 17:04:24';
        $this->link->reason = 'test_reason';
        $this->link->event_id = 3;
        $this->link->content = Str::random(32);
        $this->link->save();
        $this->link->refresh();
    }

    public function test_RestoreLink_WithValidId_RestoresLink()
    {
        $this->createLink();
        $link = $this->uc->restore(new IdBoundary($this->link->id));

        $this->assertTrue($link->deleted_at === null && $link->reason === '');
    }

    public function test_RestoreLink_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->restore(new IdBoundary(1000));
    }
}