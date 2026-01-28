<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanQuyen extends Model
{
    use HasFactory;

    protected $table = 'phan_quyen';

    protected $fillable = [
        'ten_chuc_vu',
    ];

    public function nguoiDung()
    {
        return $this->hasMany(NguoiDung::class, 'role_id');
    }
}
