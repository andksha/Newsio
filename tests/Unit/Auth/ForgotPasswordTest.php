<?php

namespace Tests\Unit\Auth;

use App\Mail\Auth\ResetPasswordMail;
use App\Model\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Auth\ForgotPasswordUseCase;
use Tests\BaseTestCase;

class ForgotPasswordTest extends BaseTestCase
{
    private ForgotPasswordUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new ForgotPasswordUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function test_SendResetPasswordEmail_WithValidEmail_SendsEmail()
    {
        Mail::fake();
        $user = $this->createUser();
        $result = $this->uc->sendResetPasswordEmail(new EmailBoundary($user->email));
        $passwordReset = PasswordReset::query()->where('email', $user->email)->first();

        $this->assertTrue($result);
        $this->assertTrue($passwordReset !== null);
        Mail::assertQueued(ResetPasswordMail::class);
    }

    /**
     * @throws ApplicationException
     */
    public function test_SendResetPasswordEmail_WithInvalidEmail_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->sendResetPasswordEmail(new EmailBoundary('test@test.test'));
    }
}