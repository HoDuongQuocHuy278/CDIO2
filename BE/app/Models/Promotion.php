<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_khuyen_mai',
        'giam_gia',
        'doi_tuong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
    ];

    public function membershipCards()
    {
        return $this->hasMany(MembershipCard::class, 'khuyen_mai_id');
    }
}
