<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $users = User::all();

        foreach ($events as $event) {
            Order::factory()->count(rand(3, 7))->create([
                'event_id' => $event->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
