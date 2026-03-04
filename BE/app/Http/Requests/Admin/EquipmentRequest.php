<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id'          => 'sometimes|exists:equipments,id',
            'name'        => 'required|string|max:255',
            'type_id'     => 'required|integer|exists:equipment_types,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'room_id'     => 'required|integer|exists:rooms,id',
            'status'      => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Tên thiết bị không được để trống',
            'type_id.required'     => 'Loại thiết bị không được để trống',
            'type_id.exists'       => 'Loại thiết bị không tồn tại',
            'supplier_id.required' => 'Nhà cung cấp không được để trống',
            'room_id.required'     => 'Phòng không được để trống',
        ];
    }
}
