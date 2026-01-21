<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'ngay_nhap',
        'tong_tien',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'import_id');
    }
}
