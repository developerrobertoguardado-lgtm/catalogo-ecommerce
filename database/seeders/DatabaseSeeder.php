<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        StoreSetting::firstOrCreate([], [
            'store_name' => 'Mi Tienda',
            'whatsapp_number' => '51999999999',
            'currency' => 'USD',
        ]);

        Category::factory(4)
            ->has(
                Product::factory(3)
                    ->has(ProductImage::factory()->primary(), 'images')
            )
            ->create();
    }
}
