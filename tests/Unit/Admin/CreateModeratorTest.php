<?php

namespace Tests\Unit\Admin;

use App\Mail\Admin\ModeratorCreatedMail;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\UseCase\Admin\CreateModeratorUseCase;
use Tests\BaseTestCase;

class CreateModeratorTest extends BaseTestCase
{
    private CreateModeratorUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new CreateModeratorUseCase();
        parent::setUp();
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_CreateModerator_WithValidEmail_CreatesModerator()
    {
        Mail::fake();
        $moderator = $this->uc->createModerator(new EmailBoundary('test@test.test'));
        $this->assertEquals('test@test.test', $moderator->email);
        Mail::assertQueued(ModeratorCreatedMail::class);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_CreateModerator_WithExistingEmail_ThrowsAlreadyExistsException()
    {
        $this->expectException(AlreadyExistsException::class);
        $this->uc->createModerator(new EmailBoundary('test@test.test'));
        $this->uc->createModerator(new EmailBoundary('test@test.test'));
    }
}