<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $toInsert = [];
        foreach (range(0, 100) as $i => $v) {
            $toInsert[] = [
                'status' => 0,
                'user_id' => 1,
                'title' => 'test',
                'basic_info' => 'test',
                'short_description' => 'test',
                'description' => 'test',
                'capacity' => 'test',
                'transport_package' => 'test',
                'customization' => 1,
                'payment_terms' => 'test',
                'delivery' => 'test',
                'min_order' => 'test',
            ];
        }

        DB::table('products')->insert($toInsert);
    }
}
