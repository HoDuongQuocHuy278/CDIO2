<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:members,email',
            'phone'            => 'required|string|max:20',
            'address'          => 'nullable|string|max:255',
            'avatar'           => 'nullable|string',

            'status'           => 'nullable|in:0,1',

            'service_id'       => 'required|integer',
            'ten_dich_vu'      => 'required|string|max:255',
            'ten_goi'          => 'required|string|max:255',
            'gia_tien'         => 'required|integer|min:0',
            'package_duration' => 'required|integer|min:1',

            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
        ];
    }
    public function messages(): array
    {
        return [
            'full_name.required'   => 'Họ tên không được để trống',
            'email.required'       => 'Email không được để trống',
            'email.email'          => 'Email không đúng định dạng',
            'email.unique'         => 'Email đã tồn tại',
            'phone.required'       => 'Số điện thoại không được để trống',

            'ten_dich_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_goi.required'     => 'Tên gói không được để trống',
            'gia_tien.required'    => 'Giá tiền không được để trống',

            'start_date.required'  => 'Ngày bắt đầu không được để trống',
            'end_date.after'       => 'Ngày kết thúc phải sau ngày bắt đầu',
        ];
    }
}
