<?php

namespace Tests\Unit\Auth;

use Illuminate\Support\Facades\Hash;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Auth\ResetPasswordUseCase;
use Tests\BaseTestCase;

class ResetPasswordTest extends BaseTestCase
{
    private ResetPasswordUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new ResetPasswordUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function test_ResetPassword_WithValidTokenAndPasswords_ResetsPassword()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset($user->email);

        $this->uc->resetPassword(
            new PasswordBoundary('test12345'),
            new PasswordBoundary('test12345'),
            new StringBoundary($passwordReset->token)
        );

        $user->refresh();

        $this->assertTrue(Hash::check('test12345', $user->password));
        $this->assertFalse(Hash::check('test1234', $user->password));
    }

    /**
     * @throws ApplicationException
     */
    public function test_ResetPassword_WithInvalidToken_ThrowsModelNotFoundException()
    {
        try {
           $this->uc->resetPassword(
               new PasswordBoundary('test12345'),
               new PasswordBoundary('test12345'),
               new StringBoundary('test')
           );
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('Password reset not found', $e->getMessage());
        }
    }

    /**
     * @throws ApplicationException
     */
    public function test_ResetPassword_WithInvalidPasswordConfirmation_ThrowsModelNotFoundException()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset($user->email);

        try {
            $this->uc->resetPassword(
                new PasswordBoundary('test12344'),
                new PasswordBoundary('test12345'),
                new StringBoundary($passwordReset->token)
            );
        } catch (InvalidOperationException $e) {
            $this->assertEquals('Passwords do not match', $e->getMessage());
        }
    }

    /**
     * @throws ApplicationException
     */
    public function test_ResetPassword_WithNonExistingUser_ThrowsModelNotFoundException()
    {
        $passwordReset = $this->createPasswordReset('test@test.test');
        try {
            $this->uc->resetPassword(
                new PasswordBoundary('test12345'),
                new PasswordBoundary('test12345'),
                new StringBoundary($passwordReset->token)
            );
        } catch (ModelNotFoundException $e) {
            $this->assertEquals('User not found', $e->getMessage());
        }
    }
}