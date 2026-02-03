<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class equipments extends Model
{
    protected $table = 'equipments';

    protected $fillable = [
        'ten_thiet_bi',
        'type_id',
        'supplier_id',
        'room_id',
        'tinh_trang',
    ];
    const TINH_TRANG_TOT = 1;
    const TINH_TRANG_BAO_TRI = 2;
    const TINH_TRANG_HONG = 3;
}
