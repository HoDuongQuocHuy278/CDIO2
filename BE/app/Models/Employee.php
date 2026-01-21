<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'ho_ten',
        'ngay_sinh',
        'sdt',
        'dia_chi',
        'email',
        'user_id',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'user_id');
    }

    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class, 'employee_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'employee_id');
    }
}
