<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        $rules = [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
            'discount' => 'required|numeric',
            'is_active' => 'boolean',
            'min_order_amount' => 'required|numeric',
            'max_usage' => 'required|integer',
        ];

        // Nếu là tạo mới (route store), thêm rule cho 'code'
        if ($this->isMethod('post')) {
            $rules['code'] = 'required|unique:vouchers';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',

            'start_date_time.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'start_date_time.date' => 'Thời gian bắt đầu không hợp lệ.',

            'end_date_time.required' => 'Vui lòng chọn thời gian kết thúc.',
            'end_date_time.date' => 'Thời gian kết thúc không hợp lệ.',
            'end_date_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',

            'discount.required' => 'Vui lòng nhập mức giảm giá.',
            'discount.numeric' => 'Giá trị giảm giá phải là số.',

            'is_active.boolean' => 'Trạng thái không hợp lệ.',
            
            'min_order_amount.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',

            'max_usage.required' => 'Vui lòng nhập số lần sử dụng tối đa.',
            'max_usage.integer' => 'Số lần sử dụng tối đa phải là số nguyên.',
        ];
    }
}