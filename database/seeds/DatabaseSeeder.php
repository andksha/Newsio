<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(EventSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(EventTagSeeder::class);
        $this->call(LinkSeeder::class);
        $this->call(WebsiteSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(Category1Seeder::class);

        // $this->call(UserSeeder::class);
    }
}
