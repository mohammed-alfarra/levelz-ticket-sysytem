<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => randomOrCreateFactory(Event::class),
            'order_id' => randomOrCreateFactory(Order::class),
        ];
    }
}
