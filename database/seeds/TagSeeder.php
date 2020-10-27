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

        for ($i = 0; $i<100;$i++) {
            $tags[] = [
                'name' => Str::random(32),
            ];
        }

        DB::table('tags')->insert($tags);
    }
}
