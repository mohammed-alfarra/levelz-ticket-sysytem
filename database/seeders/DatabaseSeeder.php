<?php

namespace Database\Seeders;

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
        //fixed seeders
        $this->call(AdminSeeder::class);

        //testing seeders
        if (! app()->environment('production')) {
            $this->call(UserSeeder::class);
            $this->call(EventSeeder::class);
        }
    }
}
