<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Newsio\Model\Category;

class EventSeeder extends Seeder
{
    private \Illuminate\Database\Eloquent\Collection $categories;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [];
        $this->categories = Category::all();

        for ($i = 0; $i<200;$i++) {
            $events[] = [
                'title' => Str::random(32),
                'category_id' => $this->categories->random()->id,
                'deleted_at' => mt_rand(0, 100) > 70 ? \Carbon\Carbon::now() : null,
            ];
        }

        $events = $this->fillEventsForGetEventsTest($events);

        DB::table('events')->insert($events);
    }

    private function fillEventsForGetEventsTest(array $events): array
    {
        $events[] = [
            'title' => 'key word TEST is searched',
            'category_id' => $this->categories->random()->id,
            'deleted_at' => null,
        ];

        $events[] = [
            'title' => 'title3',
            'category_id' => $this->categories->random()->id,
            'deleted_at' => null,
        ];

        return $events;
    }
}
