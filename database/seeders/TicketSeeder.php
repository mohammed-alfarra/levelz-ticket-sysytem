<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            Ticket::factory()->count(rand(1, 5))->create([
                'event_id' => $order->event_id,
                'order_id' => $order->id,
            ]);
        }
    }
}
