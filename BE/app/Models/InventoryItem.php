<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id',
        'product_id',
        'so_luong',
        'gia_nhap',
    ];

    public function import()
    {
        return $this->belongsTo(InventoryImport::class, 'import_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
