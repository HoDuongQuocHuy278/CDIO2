<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_nha_cung_cap',
        'sdt',
        'dia_chi',
        'email',
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'supplier_id');
    }

    public function inventoryImports()
    {
        return $this->hasMany(InventoryImport::class, 'supplier_id');
    }
}
