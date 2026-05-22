<?php

namespace App\Repositories;

use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Repositories\Interfaces\BomRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BomRepository implements BomRepositoryInterface
{
    protected $model;

    public function __construct(BomHeader $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->model->create($data);
            });
        } catch (\Exception $e) {
            Log::error('BOM Creation Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $bom = $this->find($id);
            return DB::transaction(function () use ($bom, $data) {
                $bom->update($data);
                return $bom;
            });
        } catch (\Exception $e) {
            Log::error('BOM Update Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $bom = $this->find($id);
                return $bom->delete();
            });
        } catch (\Exception $e) {
            Log::error('BOM Deletion Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        return $this->model->with(['project', 'uploader', 'lineItems'])->findOrFail($id);
    }

    public function findAll(array $filters = [])
    {
        $query = $this->model->with(['project', 'uploader']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function findByBomNumber($bomNumber)
    {
        return $this->model->where('bom_number', $bomNumber)->first();
    }

public function getLineItems($bomHeaderId, $perPage = 25)
{
    return BomLineItem::where('bom_header_id', $bomHeaderId)
        ->orderBy('line_number')
        ->paginate($perPage);
}

    public function updateInventoryStatus($bomHeaderId)
    {
        $items = BomLineItem::where('bom_header_id', $bomHeaderId)->get();
        
        foreach ($items as $item) {
            $inventory = \App\Models\Inventory::where('item_code', $item->item_code)->first();
            
            if ($inventory) {
                $available = $inventory->available_quantity;
                
                if ($available >= $item->quantity) {
                    $item->inventory_status = 'in_stock';
                } elseif ($available > 0) {
                    $item->inventory_status = 'partial';
                } else {
                    $item->inventory_status = 'out_of_stock';
                }
                
                $item->available_quantity = $available;
                $item->shortfall_quantity = max(0, $item->quantity - $available);
            } else {
                $item->inventory_status = 'out_of_stock';
                $item->available_quantity = 0;
                $item->shortfall_quantity = $item->quantity;
            }
            
            $item->save();
        }
        
        return $items;
    }
}