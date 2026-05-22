<?php

namespace App\Repositories;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryRepository
{
    protected $model;

    public function __construct(Inventory $model)
    {
        $this->model = $model;
    }

    public function findByItemCode($itemCode)
    {
        return $this->model->where('item_code', $itemCode)->first();
    }

    public function updateStock($itemCode, $quantity, $operation = 'deduct')
    {
        $inventory = $this->findByItemCode($itemCode);
        
        if (!$inventory) {
            return false;
        }

        try {
            if ($operation === 'deduct') {
                $inventory->decrement('quantity_on_hand', $quantity);
            } else {
                $inventory->increment('quantity_on_hand', $quantity);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Stock Update Failed: ' . $e->getMessage());
            return false;
        }
    }

    public function reserveStock($itemCode, $quantity)
    {
        $inventory = $this->findByItemCode($itemCode);
        
        if (!$inventory || $inventory->available_quantity < $quantity) {
            return false;
        }

        try {
            $inventory->increment('quantity_reserved', $quantity);
            return true;
        } catch (\Exception $e) {
            Log::error('Stock Reserve Failed: ' . $e->getMessage());
            return false;
        }
    }

    public function allocateReservedStock($itemCode, $quantity)
    {
        $inventory = $this->findByItemCode($itemCode);
        
        if (!$inventory || $inventory->quantity_reserved < $quantity) {
            return false;
        }

        try {
            return DB::transaction(function () use ($inventory, $quantity) {
                $inventory->decrement('quantity_on_hand', $quantity);
                $inventory->decrement('quantity_reserved', $quantity);
                return true;
            });
        } catch (\Exception $e) {
            Log::error('Stock Allocation Failed: ' . $e->getMessage());
            return false;
        }
    }
}