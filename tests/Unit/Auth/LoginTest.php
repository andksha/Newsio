<?php

namespace Tests\Unit\Auth;

use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\InvalidDataException;
use Newsio\UseCase\Auth\LoginUseCase;
use Tests\BaseTestCase;

class LoginTest extends BaseTestCase
{
    private LoginUseCase $uc;

    protected function setUp(): void
    {
        $this->uc = new LoginUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function test_Login_WithCorrectCredentials_LogsInUser()
    {
        $user = $this->createUser();
        $result = $this->uc->login(new EmailBoundary($user->email), new PasswordBoundary('test1234'));

        $this->assertTrue($result);
        $this->assertTrue(auth()->user() == true);
    }

    /**
     * @throws ApplicationException
     */
    public function test_Login_WithIncorrectCredentials_ThrowsInvalidDataException()
    {
        $this->expectException(InvalidDataException::class);
        $this->uc->login(new EmailBoundary('test@test.test'), new PasswordBoundary('test1234'));
        $this->assertTrue(!auth()->user());
    }
}