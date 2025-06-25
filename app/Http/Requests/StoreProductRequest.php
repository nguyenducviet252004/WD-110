<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần phân quyền
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
           'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File ảnh, tối đa 2MB
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.product_size_id' => 'required|exists:product_sizes,id',
            'variants.*.product_color_id' => 'required|exists:product_colors,id',
            'variants.*.quantity' => 'required|integer|min:0',
           'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File ảnh cho biến thể
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
