<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $table = 'service_packages';

    protected $fillable = [
        'service_id',
        'ten_goi',
        'thoi_han_ngay',
        'so_buoi_tap',
        'gia_tien',
    ];
}
