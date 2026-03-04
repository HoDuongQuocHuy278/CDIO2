<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'ten_dich_vu',
        'loai_dich_vu',
        'gia_tien',
        'thoi_han',
        'so_buoi',
        'mo_ta',
        'status',
    ];
    const NGUNG_BAN = 0;
    const DANG_HOAT_DONG = 1;
}
