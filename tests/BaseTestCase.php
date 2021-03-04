<?php

namespace Tests;

use App\Model\EmailConfirmation;
use App\Model\Moderator;
use App\Model\PasswordReset;
use App\Model\User;
use DatabaseSeeder;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Newsio\Model\Event;
use Newsio\Model\Link;
use Predis\Client;

class BaseTestCase extends TestCase
{
    protected static bool $migrated = false;
    protected ?DatabaseSeeder $dbSeeder = null;

    protected static bool $reseeded = false;

    protected static string $token;

    /**
     * Child tests may specify seeds they need to run again on clean table in format:
     * 'tableName' => Seeder::class
     * Such seeds are run once before each class.
     * @var array $toReseed
     */
    protected static array $toReseed = [];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dbSeeder = new DatabaseSeeder();
        $this->dbSeeder->setContainer(new Container());
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$reseeded = false;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();
        $this->reseed();

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

    protected function migrate()
    {
        if (self::$migrated === false) {
            self::$migrated = true;
            $this->artisan('migrate:fresh --seed');
//            TODO: call seeders
//            $this->dbSeeder->call($this->dbSeeder::TEST_SEEDERS);
        }
    }

    // reseed data specified in each test individually
    protected function reseed()
    {
        if (empty(static::$toReseed) || static::$reseeded) {
            return;
        }

        foreach (static::$toReseed as $tableName => $seeder) {
            DB::table($tableName)->truncate();
        }

        $this->dbSeeder->call(static::$toReseed);
        static::$reseeded = true;
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

    public function withBearerToken(): BaseTestCase
    {
        if (!isset(self::$token)) {
            self::$token = $this->post('api/login', [
                'email' => $this->createUser()->email,
                'password' => 'test1234'
            ])->json('payload.token');
        }
        $this->withHeader('Authorization', 'Bearer ' . self::$token);
        return $this;
    }
}