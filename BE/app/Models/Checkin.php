<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'thoi_gian_vao',
        'thoi_gian_ra',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
