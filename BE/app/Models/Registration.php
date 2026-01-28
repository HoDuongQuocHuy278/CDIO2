<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'package_id',
        'ngay_dang_ky',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_buoi_da_tap',
        'trang_thai',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function sessions()
    {
        return $this->hasMany(MemberSession::class, 'registration_id');
    }
}
