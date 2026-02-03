<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class equipment_types extends Model
{
    protected $table = 'equipment_types';
    protected $fillable = [
        'ten_loai',
    ];
}
