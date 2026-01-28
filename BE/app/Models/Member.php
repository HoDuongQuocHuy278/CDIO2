<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'avatar',
        'status',
        'package_name',
        'package_duration',
        'start_date',
        'end_date',
    ];

    const HOAT_DONG = 1;
    const KHONG_HOAT_DONG = 0;
    const WARNING_LEVEL_0 = 0;
    const WARNING_LEVEL_1 = 1;
    const WARNING_LEVEL_2 = 2;

}
