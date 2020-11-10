<?php

namespace Tests\Unit\Moderator;

use App\Model\Moderator;
use App\Model\ModeratorConfirmation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\InvalidOperationException;
use Newsio\Exception\ModelNotFoundException;
use Newsio\UseCase\Admin\CreateModeratorUseCase;
use Newsio\UseCase\Moderator\ConfirmModeratorUseCase;
use Tests\BaseTestCase;

class ConfirmModeratorTest extends BaseTestCase
{
    private ConfirmModeratorUseCase $uc;
    private ModeratorConfirmation $mc;
    private Moderator $moderator;

    protected function setUp(): void
    {
        $this->uc = new ConfirmModeratorUseCase();
        parent::setUp();
    }

    /**
     * @throws ApplicationException
     */
    public function createModerator()
    {
        $uc = new CreateModeratorUseCase();
        $this->moderator = $uc->createModerator(new EmailBoundary('test@test.test'));
        return $this;
    }

    public function createModeratorConfirmation()
    {
        $this->mc = ModeratorConfirmation::query()->create([
            'email' => 'test@test.test',
            'token' => Str::random(32)
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmModerator_WithValidTokenAndPasswords_ConfirmsModerator()
    {
        $this->createModerator()->createModeratorConfirmation();
        $this->uc->confirm(
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1234'),
            new StringBoundary($this->mc->token)
        );

        $this->moderator->refresh();

        $this->assertTrue(Hash::check('test1234', $this->moderator->password));
        $this->assertTrue($this->moderator->email_verified_at !== null);
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmModerator_WithInvalidToken_ThrowsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->uc->confirm(
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1234'),
            new StringBoundary('test')
        );
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmModerator_WithInvalidPasswordConfirmation_ThrowsInvalidOperationException()
    {
        $this->createModeratorConfirmation();
        $this->expectException(InvalidOperationException::class);
        $this->uc->confirm(
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1235'),
            new StringBoundary($this->mc->token)
        );
    }

    /**
     * @throws ApplicationException
     */
    public function test_ConfirmModerator_WithNonExistingUser_ThrowsModelNotFoundException()
    {
        $this->createModeratorConfirmation();
        $this->expectException(ModelNotFoundException::class);
        $this->uc->confirm(
            new PasswordBoundary('test1234'),
            new PasswordBoundary('test1234'),
            new StringBoundary($this->mc->token)
        );
    }
}