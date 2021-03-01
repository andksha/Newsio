<?php

namespace Tests\Unit\Auth;

use App\Model\EmailConfirmation;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\UseCase\RegisterBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Auth\ConfirmEmailUseCase;
use Newsio\UseCase\Auth\RegisterUseCase;
use Tests\BaseTestCase;

class ConfirmationTest extends BaseTestCase
{
    private ConfirmEmailUseCase $uc;
    private User $user;

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
        $this->user = $registerUseCase->register(new RegisterBoundary([
            'email' => 'test@test.test',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]));

        return EmailConfirmation::query()->where('email', 'test@test.test')->first();
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmEmail_WithValidToken_ConfirmsEmail()
    {
        $emailConfirmation = $this->registerUser();
        $user = $this->uc->confirm(new StringBoundary($emailConfirmation->token));
        $this->user->refresh();

        $this->assertTrue($user->email === $this->user->email && $this->user->email_verified_at !== null );
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
        $emailConfirmation = $this->createEmailConfirmation();
        try {
            $this->uc->confirm(new StringBoundary($emailConfirmation->token));
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('User not found', $e->getMessage());
        }
    }
}