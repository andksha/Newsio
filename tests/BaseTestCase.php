<?php

namespace Tests;

use App\Model\EmailConfirmation;
use App\Model\Moderator;
use App\Model\PasswordReset;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Predis\Client;

class BaseTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $client = new Client([
            'host' => config('database.redis.default.host'),
            'port' => config('database.redis.default.port'),
            'password' => config('database.redis.default.password'),
            'database' => 2
        ]);
        $client->flushdb();

        $this->artisan('migrate:fresh --seed');

    }

    public function createEvent(string $title = 'test_event')
    {
        return Event::query()->create([
            'title' => $title,
            'user_id' => 1,
            'category_id' => 1
        ]);
    }

    public function createLink(int $eventId)
    {
        $link = new Link();
        $link->content = 'https://golos.ua/vserlfvnervjn';
        $link->event_id = $eventId;
        $link->save();

        return $link;
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

    public function createModerator()
    {
        return Moderator::query()->create([
            'email' => 'test@modera.tor',
            'password' => Hash::make('test1234')
        ]);
    }

}