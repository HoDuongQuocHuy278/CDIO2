<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id'             => 'sometimes|exists:invoices,id',
            'customer'       => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'method'         => 'nullable|string',
            'status'         => 'nullable|string',
            'code'           => 'nullable|string',
            'staff'          => 'nullable|string',
            'date'           => 'nullable|date',
            'service_id'     => 'nullable|integer',
            'package_id'     => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'customer.required' => 'Tên khách hàng không được để trống',
            'amount.required'   => 'Số tiền không được để trống',
            'amount.numeric'    => 'Số tiền phải là số',
        ];
    }
}
