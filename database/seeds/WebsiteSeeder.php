<?php

use Illuminate\Database\Seeder;
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
            ],
            [
                'domain' => 'https://biz.censor.net/',
                'approved' => true,
                'reason' => '',
            ],
            [
                'domain' => 'https://ru.krymr.com/',
                'approved' => true,
                'reason' => '',
            ],
            [
                'domain' => 'https://www.rbc.ua/',
                'approved' => true,
                'reason' => '',
            ],
            [
                'domain' => 'https://golos.ua/',
                'approved' => true,
                'reason' => '',
            ],
            [
                'domain' => 'https://www.imdb.com/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason ',
            ],
            [
                'domain' => 'https://www.google.com/',
                'approved' => false,
                'reason' => 'some reasonsome reason some reason some reason some reason ',
            ],
            [
                'domain' => 'https://www.pinterest.co.uk/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason',
            ],
            [
                'domain' => 'https://www.test.ua/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason some reason some reason ',
            ],
            [
                'domain' => 'https://test2.ua/',
                'approved' => false,
                'reason' => 'some reason some reason some reason some reason some reason some reason some reason some reason some reason ',
            ],
        ];

        DB::table('websites')->insert($websites);
    }
}
