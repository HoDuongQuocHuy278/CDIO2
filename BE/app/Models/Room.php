<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_phong',
    ];

    public function roomEquipments()
    {
        return $this->hasMany(RoomEquipment::class, 'room_id');
    }
}
