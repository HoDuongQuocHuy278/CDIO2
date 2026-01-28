<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'thoi_han_thang',
        'so_buoi_tap',
        'gia_tien',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'package_id');
    }
}
