<?php

namespace Tests;

use App\Model\EmailConfirmation;
use App\Model\PasswordReset;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Newsio\Model\Event;

class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh --seed');
    }

    public function createEvent(string $title = 'test_incrementing')
    {
        return Event::query()->create([
            'title' => $title,
            'user_id' => 1,
            'category_id' => 1
        ]);
    }

    public function createUser()
    {
        return User::query()->create([
            'email' => 'test@test.test',
            'password' => Hash::make('test1234')
        ]);
    }

    public function createEmailConfirmation()
    {
        return EmailConfirmation::query()->create([
            'email' => 'test@test.test',
            'token' => 'testtesttest'
        ]);
    }


    public function createPasswordReset(string $email)
    {
        return PasswordReset::query()->create([
            'email' => $email,
            'token' => Str::random(32)
        ]);
    }

}