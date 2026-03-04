<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id'           => 'sometimes|exists:services,id',
            'ten_dich_vu'  => 'required|string|max:255',
            'loai_dich_vu' => 'required|string|max:255',
            'gia_tien'     => 'required|numeric|min:0',
            'thoi_han'     => 'nullable|string|max:50',
            'so_buoi'      => 'nullable|string|max:50',
            'status'       => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_dich_vu.required'  => 'Tên dịch vụ không được để trống',
            'loai_dich_vu.required' => 'Loại dịch vụ không được để trống',
            'gia_tien.required'     => 'Giá tiền không được để trống',
            'gia_tien.numeric'      => 'Giá tiền phải là số',
        ];
    }
}
