<?php

namespace Tests\Unit\Auth;

use App\Mail\Auth\RegisteredMail;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Exception\BoundaryException;
use Newsio\UseCase\Auth\RegisterUseCase;
use Tests\BaseTestCase;

class RegisterTest extends BaseTestCase
{
    private RegisterUseCase $uc;
    
    protected function setUp(): void
    {
        $this->uc = new RegisterUseCase();
        parent::setUp();
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithValidEmailAndPasswords_RegistersUserAndSendsEmail()
    {
        Mail::fake();
        $user = $this->uc->register(
            new EmailBoundary('test@test.tаа'),
            new PasswordBoundary('12345678'),
            new PasswordBoundary('12345678')
        );

        Mail::assertQueued(RegisteredMail::class);
        $this->assertEquals('test@test.tаа', $user->email);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidEmail_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $this->uc->register(
            new EmailBoundary('test@test.t'),
            new PasswordBoundary('12345678'),
            new PasswordBoundary('12345678')
        );
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidPassword_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $this->uc->register(
            new EmailBoundary('test@test.taa'),
            new PasswordBoundary('1234'),
            new PasswordBoundary('1234')
        );
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidPasswordConfirmation_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $this->uc->register(
            new EmailBoundary('test@test.taa'),
            new PasswordBoundary('12345678'),
            new PasswordBoundary('1234567910')
        );
    }
}