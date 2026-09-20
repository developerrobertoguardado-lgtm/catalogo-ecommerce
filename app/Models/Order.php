<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'whatsapp_number',
        'total',
        'customer_name',
        'customer_phone',
        'delivery_zone',
        'delivery_address',
        'delivery_city',
        'delivery_notes',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }

    public function isDelivered(): bool
    {
        return $this->status === 'ENTREGADO';
    }

    public function canBeEdited(): bool
    {
        return !$this->isDelivered();
    }
}
