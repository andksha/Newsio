<?php

namespace Tests;

use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Auth\RegisterUseCase;

class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh --seed');
    }

    /**
     * @throws ApplicationException
     */
    public function createUser()
    {
        $registerUseCase = new RegisterUseCase();
        return $registerUseCase->register(
            new EmailBoundary('test@test.test'),
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1234')
        );
    }
}