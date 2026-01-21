<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_san_pham',
        'gia_ban',
        'so_luong',
    ];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'product_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'product_id');
    }
}
