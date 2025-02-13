<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'is_active' => true,
        ];
    }
}
