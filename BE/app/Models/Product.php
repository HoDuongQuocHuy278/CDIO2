<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'ten_san_pham',
        'gia_ban',
        'so_luong',
        'danh_muc',
    ];

}
