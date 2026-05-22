<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_number',
        'bom_header_id',
        'bom_line_item_id',
        'item_code',
        'item_description',
        'allocated_quantity',
        'original_required_quantity',
        'allocated_to',
        'allocated_by',
        'allocated_at',
        'allocation_details'
    ];

    protected $casts = [
        'allocated_quantity' => 'decimal:3',
        'original_required_quantity' => 'decimal:3',
        'allocated_at' => 'datetime',
        'allocation_details' => 'array'
    ];

    public function bomHeader()
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function bomLineItem()
    {
        return $this->belongsTo(BomLineItem::class);
    }
}