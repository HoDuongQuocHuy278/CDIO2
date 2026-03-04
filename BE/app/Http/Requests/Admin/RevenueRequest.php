<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class RevenueRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'amount'      => 'required|numeric|min:0',
            'source'      => 'required|string|max:255',
            'revenue_date'=> 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'       => 'Số tiền không được để trống',
            'source.required'       => 'Nguồn thu không được để trống',
            'revenue_date.required' => 'Ngày thu không được để trống',
        ];
    }
}
