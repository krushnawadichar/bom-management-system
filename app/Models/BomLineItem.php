<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_header_id',
        'item_code',
        'part_number',
        'description',
        'part_code',
        'material_specification',
        'size_of_material',
        'quantity',
        'uom',
        'purchase_technical_spec_no',
        'stock_verification',
        'remarks',
        'allocated_to',
        'inventory_status',
        'available_quantity',
        'allocated_quantity',
        'shortfall_quantity',
        'line_number',
        'raw_data'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'allocated_quantity' => 'decimal:3',
        'shortfall_quantity' => 'decimal:3',
        'raw_data' => 'array'
    ];

    public function bomHeader()
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function purchaseIntent()
    {
        return $this->hasOne(PurchaseIntent::class);
    }

    public function allocation()
    {
        return $this->hasOne(MaterialAllocation::class);
    }

// app/Models/BomLineItem.php - Add these accessor methods
public function getStatusIconAttribute()
{
    return match($this->inventory_status) {
        'in_stock' => '✅',
        'partial' => '⚠️',
        'out_of_stock' => '❌',
        default => '🔄'
    };
}

public function getStatusClassAttribute()
{
    return match($this->inventory_status) {
        'in_stock' => 'success',
        'partial' => 'warning',
        'out_of_stock' => 'danger',
        default => 'secondary'
    };
}
}