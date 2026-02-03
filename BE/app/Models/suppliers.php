<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class suppliers extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'ten_nha_cung_cap',
        'so_dien_thoai',
        'email',
        'website',
        'dia_chi',
        'tinh_trang',
    ];
    const HOAT_DONG = 1;
    const HET_CUNG_CAP = 0;
}
