<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'pt',
        'customer',
        'day',
        'start',
        'end',
    ];

    protected $casts = [
        'pt' => 'array',
    ];
}
