<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'registration_id',
        'thoi_gian_vao',
        'thoi_gian_ra',
        'thoi_luong_phut',
        'ghi_chu',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
