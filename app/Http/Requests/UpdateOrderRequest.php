<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:PENDIENTE,EN_PROCESO,ENVIADO,ENTREGADO'],
            'notes' => ['nullable', 'string'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'delivery_zone' => ['nullable', 'string', 'max:20'],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'delivery_city' => ['nullable', 'string', 'max:120'],
            'delivery_notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'integer', 'exists:order_items,id'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'El pedido debe tener al menos un producto.',
            'items.min' => 'El pedido debe tener al menos un producto.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
        ];
    }
}