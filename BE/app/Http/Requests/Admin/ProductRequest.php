<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id'           => 'sometimes|exists:products,id',
            'ten_san_pham' => 'required|string|max:255',
            'danh_muc'     => 'required|string|max:255',
            'gia_ban'      => 'required|numeric|min:0',
            'so_luong'     => 'required|integer|min:0',
            'status'       => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_san_pham.required' => 'Tên sản phẩm không được để trống',
            'danh_muc.required'     => 'Danh mục không được để trống',
            'gia_ban.required'      => 'Giá bán không được để trống',
            'so_luong.required'     => 'Số lượng không được để trống',
        ];
    }
}
