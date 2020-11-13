<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Newsio\Model\Category;

class EventSeeder extends Seeder
{
    private \Illuminate\Database\Eloquent\Collection $categories;
    private \Illuminate\Database\Eloquent\Collection $users;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [];

        $this->users = User::all();
        $this->categories = Category::all();

        for ($i = 0; $i < 20; $i++) {
            $events[] = [
                'title' => Str::random(32),
                'user_id' => $this->users->random()->id,
                'category_id' => $this->categories->random()->id,
                'deleted_at' => null,
            ];
        }

        $events = $this->fillEventsForGetEventsTest($events);

        DB::table('events')->insert($events);
    }

    private function fillEventsForGetEventsTest(array $events): array
    {
        $events[] = [
            'title' => 'key word TEST is searched',
            'user_id' => $this->users->random()->id,
            'category_id' => $this->categories->random()->id,
            'deleted_at' => null,
        ];

        $events[] = [
            'title' => 'sdfgdstest',
            'user_id' => $this->users->random()->id,
            'category_id' => 3,
            'deleted_at' => null,
        ];

        $events[] = [
            'title' => 'title3',
            'user_id' => $this->users->random()->id,
            'category_id' => $this->categories->random()->id,
            'deleted_at' => null,
        ];

        $events[] = [
            'title' => 'title4',
            'user_id' => $this->users->random()->id,
            'category_id' => 3,
            'deleted_at' => null,
        ];


        $events[] = [
            'title' => 'test5',
            'user_id' => $this->users->random()->id,
            'category_id' => 3,
            'deleted_at' => null,
        ];

        $events[] = [
            'title' => 'test6',
            'user_id' => $this->users->random()->id,
            'category_id' => 2,
            'deleted_at' => null,
        ];

        return $events;
    }
}
