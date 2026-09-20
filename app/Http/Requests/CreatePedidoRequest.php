<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s-]+$/'],
            'delivery_zone' => ['required', 'in:lima,provincias'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_city' => ['required', 'string', 'max:120'],
            'delivery_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
