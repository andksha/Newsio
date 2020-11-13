<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [];

        for ($i = 0; $i<10;$i++) {
            $tags[] = [
                'name' => Str::random(mt_rand(3, 7)),
            ];
        }

        $tags = $this->fillTagsForGetEventsTest($tags);

        DB::table('tags')->insert($tags);
    }

    private function fillTagsForGetEventsTest(array $tags): array
    {
        $tags[] = [
            'name' => 'test'
        ];

        $tags[] = [
            'name' => 'tag1'
        ];

        return $tags;
    }
}
