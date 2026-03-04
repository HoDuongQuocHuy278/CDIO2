<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'ho_ten',
        'ngay_sinh',
        'sdt',
        'dia_chi',
        'email',
        'chuc_vu',
        'luong',
        'ngay_vao_lam',
        'trang_thai',
        'user_id',
        'luong_co_dinh',
    ];

    const NGHI_LAM = 0;
    const DANG_LAM = 1;
    const TAM_NGHI = 2;
}
