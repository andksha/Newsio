<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Newsio\Model\Event;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $links = [];
        $events = Event::all();

        for ($i = 0; $i<100;$i++) {
            $links[] = [
                'content' => Str::random(32),
                'event_id' => $events->random()->id,
            ];
        }

        $links[] = [
            'content' => 'lsbjhsejtestsnvldfjnv',
            'event_id' => $events->where('title', 'title3')->first()->id
        ];

        DB::table('links')->insert($links);
    }
}
