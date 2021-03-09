<?php

namespace Tests\Unit\Moderator;

use Illuminate\Support\Str;
use Newsio\Boundary\IdBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Link;
use Newsio\Model\Operation;
use Newsio\Repository\RemovedEventRepository;
use Newsio\UseCase\Moderator\RestoreLinkUseCase;
use Tests\BaseTestCase;
use Tests\Unit\OperationTest;

class RestoreLinkTest extends BaseTestCase
{
    private RestoreLinkUseCase $uc;
    private Link $link;
    private OperationTest $ot;

    protected function setUp(): void
    {
        $this->uc = new RestoreLinkUseCase(new RemovedEventRepository());
        $this->ot = new OperationTest($this);
        parent::setUp();
    }

    private function createDeletedLink()
    {
        $this->link = new Link();
        $this->link->deleted_at = '2020-11-06 17:04:24';
        $this->link->reason = 'test_reason';
        $this->link->event_id = 3;
        $this->link->content = Str::random(32);
        $this->link->save();
        $this->link->refresh();
    }

    /**
     * @throws ApplicationException
     */
    public function test_RestoreLink_WithValidId_RestoresLink()
    {
        $this->createDeletedLink();
        $link = $this->uc->restore(new IdBoundary($this->link->id));

        $this->assertTrue($link->deleted_at === null && $link->reason === '');
        $this->ot->assertOperationsCount(Operation::OT_RESTORED, Operation::MT_EVENT, 1);
    }

    /**
     * @throws ApplicationException
     */
    public function test_RestoreLink_WithNonExistingId_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->restore(new IdBoundary(1000));
    }
}