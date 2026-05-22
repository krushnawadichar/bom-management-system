<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'item_code',
        'item_name',
        'description',
        'material_grade',
        'uom',
        'quantity_on_hand',
        'quantity_reserved',
        'minimum_stock_level',
        'supplier_name',
        'location',
        'specifications'
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:3',
        'quantity_reserved' => 'decimal:3',
        'minimum_stock_level' => 'decimal:3',
        'specifications' => 'array'
    ];

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity_on_hand - quantity_reserved <= minimum_stock_level');
    }

    public function reserve($quantity)
    {
        if ($this->available_quantity >= $quantity) {
            $this->increment('quantity_reserved', $quantity);
            return true;
        }
        return false;
    }

    public function allocate($quantity)
    {
        if ($this->quantity_reserved >= $quantity) {
            $this->decrement('quantity_on_hand', $quantity);
            $this->decrement('quantity_reserved', $quantity);
            return true;
        }
        return false;
    }

}