<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->id;
        return [
            'id'            => 'sometimes|exists:employees,id',
            'ho_ten'        => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email,' . $id,
            'sdt'           => 'required|string|max:20',
            'chuc_vu'       => 'nullable|string|max:255',
            'trang_thai'    => 'nullable|boolean',
            'luong_co_dinh' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Họ tên không được để trống',
            'email.required'  => 'Email không được để trống',
            'email.email'     => 'Email không đúng định dạng',
            'email.unique'    => 'Email đã tồn tại',
            'sdt.required'    => 'Số điện thoại không được để trống',
        ];
    }
}
