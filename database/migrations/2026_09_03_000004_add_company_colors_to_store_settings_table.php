<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('menu_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'button_color', 'menu_color', 'background_color', 'text_color']);
        });
    }
};
