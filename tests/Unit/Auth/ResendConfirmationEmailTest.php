<?php

namespace Tests\Unit\Auth;

use App\Mail\Auth\RegisteredMail;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
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

    private function createUser()
    {
        $this->email = 'test@test.ttt';

        User::query()->create([
            'email' => $this->email,
            'password' => Hash::make('test1234')
        ]);
    }

    public function test_Resend_WithValidEmail_ResentsConfirmationEmail()
    {
        Mail::fake();
        $this->createUser();
        $this->uc->resend(new EmailBoundary($this->email));
        Mail::assertQueued(RegisteredMail::class);
    }

    public function test_Resend_WithNonExistingEmail_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->resend(new EmailBoundary('test2@test.ttt'));
    }
}