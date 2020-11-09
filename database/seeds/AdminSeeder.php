<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'email' => 'andrewafk2000@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('test1234')
        ];

        DB::table('admins')->insert($admin);
    }
}