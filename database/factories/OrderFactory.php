<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => randomOrCreateFactory(Event::class),
            'user_id' => randomOrCreateFactory(User::class),
        ];
    }
}
