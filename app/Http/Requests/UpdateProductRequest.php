<?php

namespace App\Http\Requests;

use App\Models\ProductImage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'max:4096'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $producto = $this->route('producto');
            $existentes = $producto?->images()->count() ?? 0;
            $nuevas = count($this->file('fotos', []));

            if ($existentes + $nuevas > ProductImage::MAX_IMAGES_PER_PRODUCT) {
                $validator->errors()->add('fotos', 'Un producto admite máximo 4 fotos.');
            }
        });
    }
}
