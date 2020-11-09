<?php

namespace Tests\Unit\Auth;

use App\Mail\Auth\RegisteredMail;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Auth\ResendConfirmationEmailUseCase;
use Tests\BaseTestCase;

class ResendConfirmationEmailTest extends BaseTestCase
{
    private string $email;
    private ResendConfirmationEmailUseCase $uc;
    
    protected function setUp(): void
    {
        $this->uc = new ResendConfirmationEmailUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function test_Resend_WithValidEmail_ResentsConfirmationEmail()
    {
        Mail::fake();
        $user = $this->createUser();
        $this->uc->resend(new EmailBoundary($user->email));
        Mail::assertQueued(RegisteredMail::class);
    }

    /**
     * @throws ApplicationException
     */
    public function test_Resend_WithNonExistingEmail_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->resend(new EmailBoundary('test2@test.ttt'));
    }
}