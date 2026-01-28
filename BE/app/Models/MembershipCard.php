<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'ma_the',
        'khuyen_mai_id',
        'ngay_cap',
        'ngay_het_han',
        'trang_thai',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'khuyen_mai_id');
    }
}
