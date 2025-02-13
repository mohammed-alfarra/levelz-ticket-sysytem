<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'User',
            'email' => 'user@user.com',
            'is_active' => true,
        ]);

        User::factory(20)->create();
    }
}
