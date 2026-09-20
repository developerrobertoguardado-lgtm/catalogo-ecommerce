<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 30)->nullable();
            $table->string('delivery_zone', 20)->nullable();
            $table->string('delivery_address', 500)->nullable();
            $table->string('delivery_city', 120)->nullable();
            $table->text('delivery_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name', 'customer_phone', 'delivery_zone',
                'delivery_address', 'delivery_city', 'delivery_notes',
            ]);
        });
    }
};
