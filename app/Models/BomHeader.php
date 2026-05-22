<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomHeader extends Model
{
    use HasFactory;

    protected $table = 'bom_headers';

    protected $fillable = [
        'bom_number',
        'revision',
        'project_id',
        'file_name',
        'file_path',
        'original_bom_number',
        'drawing_number',
        'capacity',
        'bom_date',
        'status',
        'uploaded_by',
        'processed_at',
        'error_message'
    ];

    protected $casts = [
        'bom_date' => 'date',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function lineItems()
    {
        return $this->hasMany(BomLineItem::class, 'bom_header_id');
    }

    public function purchaseIntents()
    {
        return $this->hasMany(PurchaseIntent::class);
    }

    public function allocations()
    {
        return $this->hasMany(MaterialAllocation::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->bom_number} (Rev. {$this->revision})";
    }
}