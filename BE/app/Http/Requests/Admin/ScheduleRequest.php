<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id'       => 'nullable|integer',
            'pt'       => 'required|array',
            'customer' => 'nullable|string|max:255',
            'day'      => 'required|string',
            'start'    => 'required|integer',
            'end'      => 'required|integer|gt:start',
        ];
    }

    public function messages(): array
    {
        return [
            'pt.required'    => 'Nhân viên không được để trống',
            'pt.array'       => 'Trường nhân viên không hợp lệ',
            'day.required'   => 'Thứ trong tuần không được để trống',
            'start.required' => 'Giờ bắt đầu không được để trống',
            'end.required'   => 'Giờ kết thúc không được để trống',
            'end.gt'         => 'Giờ kết thúc phải lớn hơn giờ bắt đầu',
        ];
    }
}
