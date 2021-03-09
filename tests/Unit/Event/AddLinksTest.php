<?php

namespace Tests\Unit\Event;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\Exception\InvalidWebsiteException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Newsio\Model\Operation;
use Newsio\UseCase\AddLinksUseCase;
use Tests\BaseTestCase;
use Tests\Unit\OperationTest;

class AddLinksTest extends BaseTestCase
{
    private AddLinksUseCase $uc;
    private OperationTest $ot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uc = new AddLinksUseCase();
        $this->ot = new OperationTest($this);
    }

    /**
     * @throws ApplicationException
     */
    public function test_AddLinks_WithValidIdAndLinks_AddsLinks()
    {
        $event = Event::query()->first();
        $this->uc->addLinks(new IdBoundary($event->id), new LinksBoundary(['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef']));
        $links = Link::query()->whereIn('content',
            ['https://www.radiosvoboda.org/event/ergerg%435%324r', 'https://biz.censor.net/event/ewfwef']
        )->count();

        $this->assertEquals(2, $links);
        $this->ot->assertOperationsCount(Operation::OT_CREATED, Operation::MT_EVENT, 1);
    }

    /**
     * @throws ApplicationException
     */
    public function test_AddLinks_WithNonExistingEvent_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->addLinks(new IdBoundary(1000), new LinksBoundary(['https://test.com/test', 'https://test2.com/test']));
        $this->ot->assertOperationsCount(Operation::OT_CREATED, Operation::MT_EVENT, 0);
    }

    /**
     * @throws ApplicationException
     */
    public function test_AddLinks_WithUnverifiedWebsite_ThrowsInvalidWebsiteException()
    {
        $event = Event::query()->first();
        $this->expectException(InvalidWebsiteException::class);
        $this->uc->addLinks(new IdBoundary($event->id), new LinksBoundary(['https://test.com/test', 'https://test2.com/test']));
        $this->ot->assertOperationsCount(Operation::OT_CREATED, Operation::MT_EVENT, 0);
    }

    /**
     * @throws ApplicationException
     */
    public function test_AddLinks_WithMoreThan3Links_ThrowsBoundaryException()
    {
        $event = Event::query()->first();
        $this->expectException(BoundaryException::class);
        $this->uc->addLinks(new IdBoundary($event->id), new LinksBoundary([
            'https://www.radiosvoboda.org/',
            'https://test2.com',
            'https://test3.com',
            'https://test4.com'
        ]));
        $this->ot->assertOperationsCount(Operation::OT_CREATED, Operation::MT_EVENT, 0);
    }

    /**
     * @throws ApplicationException
     */
    public function test_AddLinks_WithDomainOnly_ThrowsInvalidWebsiteException()
    {
        $event = Event::query()->first();
        $this->expectException(BoundaryException::class);
        $this->uc->addLinks(new IdBoundary($event->id), new LinksBoundary(['https://www.radiosvoboda.org/', 'https://test2.com']));
        $this->ot->assertOperationsCount(Operation::OT_CREATED, Operation::MT_EVENT, 0);
    }
}