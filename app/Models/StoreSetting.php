<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    /** @use HasFactory<\Database\Factories\StoreSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'store_name',
        'whatsapp_number',
        'currency',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'store_name' => config('app.name'),
            'whatsapp_number' => config('services.whatsapp.number', ''),
            'currency' => 'USD',
        ]);
    }
}
