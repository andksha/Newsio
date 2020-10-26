<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [];

        for ($i = 0; $i<10;$i++) {
            $categories[] = [
                'name' => Str::random(32),
            ];
        }

        DB::table('categories')->insert($categories);
    }
}
