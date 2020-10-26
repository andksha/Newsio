<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Newsio\Model\Category;
use Newsio\Model\Tag;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [];
        $categories = Category::all();
        $tags = Tag::all();

        for ($i = 0; $i<10;$i++) {
            $events[] = [
                'title' => Str::random(32),
                'tags' => $tags->random()->name,
                'links' => Str::random(16),
                'category_id' => $categories->random()->id,
            ];
        }

        DB::table('events')->insert($events);
    }
}
