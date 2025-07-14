<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $voucherId = $this->route('voucher'); // lấy ID từ route nếu có (dùng cho update)

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vouchers', 'code')->ignore($voucherId),
            ],
            'discount_value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'total_min' => 'nullable|numeric|min:0',
            'total_max' => 'nullable|numeric|gt:total_min',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date|after_or_equal:start_day',
            'is_active' => 'boolean',
        ];
    }

     public function attributes()
    {
        return [
            'code' => 'mã giảm giá',
            'discount_value' => 'giá trị giảm',
            'quantity' => 'số lượng',
            'total_min' => 'tổng tối thiểu',
            'total_max' => 'tổng tối đa',
            'start_day' => 'ngày bắt đầu',
            'end_day' => 'ngày kết thúc',
        ];
    }

     public function messages()
    {
        return [
            'required' => ':attribute không được để trống.',
            'unique' => ':attribute đã tồn tại.',
            'numeric' => ':attribute phải là số.',
            'integer' => ':attribute phải là số nguyên.',
            'min' => ':attribute phải lớn hơn :min.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'date' => ':attribute phải là ngày hợp lệ.',
            'after_or_equal' => ':attribute phải sau hoặc bằng ngày bắt đầu.',
            'gt' => ':attribute phải lớn hơn tổng tối thiểu.',
        ];
    }
}
