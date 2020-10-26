<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Newsio\Model\Event;
use Newsio\Model\Tag;

class EventTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventTags = [];
        $events = Event::all();
        $tags = Tag::all();

        for ($i = 0; $i<100;$i++) {
            $eventTags[] = [
                'event_id' => $events->random()->id,
                'tag_id' => $tags->random()->id,
            ];
        }

        DB::table('events_tags')->insert($eventTags);
    }
}
