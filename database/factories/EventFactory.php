<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'description' => $this->faker->paragraph,
            'quota' => 100,
            'start_date' => now()->addHour()->format('Y-m-d H:i:00'),
            'end_date' => now()->addHours(rand(3, 6))->format('Y-m-d H:i:s'),
        ];
    }
}
