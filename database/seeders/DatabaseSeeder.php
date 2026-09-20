<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')],
        );

        User::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo', 'password' => Hash::make('demo12345')]
        );

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
