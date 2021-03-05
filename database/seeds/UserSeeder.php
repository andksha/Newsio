<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];
        $password = Hash::make('test1234');

        $users[] = [
            'email' => 'test-srthertherth@test.test',
            'password' => $password
        ];

        for ($i = 0; $i<10;$i++) {
            $users[] = [
                'email' => Str::random(5) . '@mail.com',
                'password' => $password
            ];
        }

        DB::table('users')->insert($users);
    }
}