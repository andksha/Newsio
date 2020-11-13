<?php

use Carbon\Carbon;
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
        $tags = Tag::query()->where('name', '!=', 'test')->get();
        $testTag = Tag::query()->where('name', 'test')->first();
        $testTag2 = Tag::query()->where('name', 'tag1')->first();
        $events = Event::all();
        $testEvent = $events->where('title', 'sdfgdstest')->first();

        for ($i = 0; $i<100;$i++) {
            $timestamp = Carbon::now()->subMinutes(mt_rand(0, 200));
            $eventTags[] = [
                'event_id' => $events->random()->id,
                'tag_id' => $tags->random()->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        $timestamp = Carbon::now()->subMinutes(mt_rand(0, 200));

        $eventTags[] = [
            'event_id' => $events->random()->id,
            'tag_id' => $testTag->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        $eventTags[] = [
            'event_id' => $testEvent->id,
            'tag_id' => $testTag2->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        DB::table('events_tags')->insert($eventTags);
    }
}
