<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'whatsapp_number' => fake()->numerify('51#########'),
            'total' => fake()->randomFloat(2, 5, 500),
        ];
    }
}
