<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $websites = [
            [
                'domain' => 'https://www.radiosvoboda.org/',
                'approved' => true,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://biz.censor.net/',
                'approved' => true,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://ru.krymr.com/',
                'approved' => true,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://www.rbc.ua/',
                'approved' => true,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://golos.ua/',
                'approved' => true,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://www.imdb.com/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason ',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://www.google.com/',
                'approved' => false,
                'reason' => 'some reasonsome reason some reason some reason some reason ',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://www.pinterest.co.uk/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://www.test.ua/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason some reason some reason ',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://test2.ua/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason some reason some reason some reason ',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://test3.ua/',
                'approved' => null,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
            [
                'domain' => 'https://test4.ua/',
                'approved' => null,
                'reason' => '',
                'created_at' => Carbon::now()->subMinutes(mt_rand(0, 3600))
            ],
        ];

        DB::table('websites')->insert($websites);
    }
}
