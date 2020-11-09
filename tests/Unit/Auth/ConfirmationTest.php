<?php

namespace Tests\Unit\Auth;

use App\Model\EmailConfirmation;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Auth\ConfirmEmailUseCase;
use Newsio\UseCase\Auth\RegisterUseCase;
use Tests\BaseTestCase;

class ConfirmationTest extends BaseTestCase
{
    private ConfirmEmailUseCase $uc;
    private User $user;
    private EmailConfirmation $emailConfirmation;

    protected function setUp(): void
    {
        $this->uc = new ConfirmEmailUseCase();
        Mail::fake();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    private function registerUser()
    {
        $registerUseCase = new RegisterUseCase();
        $this->user = $registerUseCase->register(
            new EmailBoundary('test@test.test'),
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1234')
        );

        $this->emailConfirmation = EmailConfirmation::query()->where('email', 'test@test.test')->first();
    }

    private function createEmailConfirmation()
    {
        $this->emailConfirmation = EmailConfirmation::query()->create([
            'email' => 'test@test.test',
            'token' => 'testtesttest'
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmEmail_WithValidToken_ConfirmsEmail()
    {
        $this->registerUser();
        $user = $this->uc->confirm(new StringBoundary($this->emailConfirmation->token));

        $this->assertTrue($user->email === $this->user->email && $user->email_verified_at !== null);
    }

    public function test_ConfirmEmail_WithInvalidToken_ThrowsModelNotFoundException()
    {
        try {
            $this->uc->confirm(new StringBoundary('test'));
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('Email confirmation not found', $e->getMessage());
        }
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmEmail_WithNonExistingUser_ThrowsModelNotFoundException()
    {
        $this->createEmailConfirmation();
        try {
            $this->uc->confirm(new StringBoundary($this->emailConfirmation->token));
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('User not found', $e->getMessage());
        }
    }
}