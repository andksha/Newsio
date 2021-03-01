<?php

namespace Tests\Unit\Auth;

use App\Mail\Auth\RegisteredMail;
use Illuminate\Support\Facades\Mail;
use Newsio\Boundary\UseCase\RegisterBoundary;
use Newsio\Exception\BoundaryException;
use Newsio\Exception\InvalidOperationException;
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
        $user = $this->uc->register(new RegisterBoundary([
            'email' => 'test@test.taa',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]));

        Mail::assertQueued(RegisteredMail::class);
        $this->assertEquals('test@test.taa', $user->email);
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidEmail_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $this->uc->register(new RegisterBoundary([
            'email' => 'test@test.t',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]));
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidPassword_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $this->uc->register(new RegisterBoundary([
            'email' => 'test@test.taa',
            'password' => '1234',
            'password_confirmation' => '1234',
        ]));
    }

    /**
     * @throws \Newsio\Contract\ApplicationException
     */
    public function test_Register_WithInvalidPasswordConfirmation_ThrowsBoundaryException()
    {
        $this->expectException(InvalidOperationException::class);
        $this->uc->register(new RegisterBoundary([
            'email' => 'test@test.taa',
            'password' => '12345678',
            'password_confirmation' => '12345678910',
        ]));
    }
}