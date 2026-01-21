<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_ca',
        'gio_bat_dau',
        'gio_ket_thuc',
    ];

    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class, 'shift_id');
    }
}
