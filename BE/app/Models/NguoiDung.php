<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class NguoiDung extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'nguoi_dung';

    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'role_id',
        'ngay_tao',
    ];

    protected $hidden = [
        'mat_khau',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function phanQuyen()
    {
        return $this->belongsTo(PhanQuyen::class, 'role_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
}
