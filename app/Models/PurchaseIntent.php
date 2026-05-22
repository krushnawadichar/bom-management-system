<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'intent_number',
        'batch_number',
        'bom_header_id',
        'bom_line_item_id',
        'item_code',
        'item_description',
        'material_specification',
        'required_quantity',
        'available_quantity',
        'shortfall_quantity',
        'priority',
        'status',
        'raised_by',
        'acknowledged_by',
        'acknowledged_at',
        'po_reference',
        'remarks'
    ];

    protected $casts = [
        'required_quantity' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'shortfall_quantity' => 'decimal:3',
        'acknowledged_at' => 'datetime'
    ];

    public function bomHeader()
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function bomLineItem()
    {
        return $this->belongsTo(BomLineItem::class);
    }

    public function raiser()
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function acknowledger()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function acknowledge()
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now()
        ]);
    }

    public function markPoRaised($poReference)
    {
        $this->update([
            'status' => 'po_raised',
            'po_reference' => $poReference
        ]);
    }
}