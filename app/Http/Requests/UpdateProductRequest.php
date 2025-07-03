<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần phân quyền
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->product->id,
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->product->id,
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
