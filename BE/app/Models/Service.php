<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_dich_vu',
        'mo_ta',
    ];

    public function packages()
    {
        return $this->hasMany(ServicePackage::class, 'service_id');
    }
}
