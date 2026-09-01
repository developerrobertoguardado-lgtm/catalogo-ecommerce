<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StoreSetting>
 */
class StoreSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'store_name' => fake()->company(),
            'whatsapp_number' => fake()->numerify('51#########'),
            'currency' => 'USD',
        ];
    }
}
