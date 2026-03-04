<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'member_id'     => 'required|exists:members,id',
            'check_in_type' => 'nullable|string|max:50',
            'status'        => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Mã thành viên không được để trống',
            'member_id.exists'   => 'Thành viên không tồn tại',
        ];
    }
}
