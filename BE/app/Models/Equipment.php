<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_thiet_bi',
        'type_id',
        'supplier_id',
        'tinh_trang',
    ];

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function roomEquipments()
    {
        return $this->hasMany(RoomEquipment::class, 'equipment_id');
    }

    public function maintenances()
    {
        return $this->hasMany(EquipmentMaintenance::class, 'equipment_id');
    }
}
