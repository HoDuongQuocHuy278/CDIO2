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

            'package_id'       => 'nullable|integer',
            'thoi_han'         => 'required|integer|min:1',
            'start_date'       => 'required|date',
            'face_image'       => 'required|string',
            
            'package_duration' => 'nullable|integer',
            'end_date'         => 'nullable|date',
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

            'thoi_han.required'    => 'Thời hạn không được để trống',
            'start_date.required'  => 'Ngày bắt đầu không được để trống',
            'face_image.required'  => 'Ảnh khuôn mặt không được để trống',
        ];
    }
}
