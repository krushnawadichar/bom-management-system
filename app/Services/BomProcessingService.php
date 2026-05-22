<?php

namespace App\Services;

use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Models\PurchaseIntent;
use App\Models\MaterialAllocation;
use App\Models\Inventory;
use App\Jobs\ProcessInventoryCheck;
use App\Jobs\CreatePurchaseIntents;
use App\Jobs\AllocateMaterials;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BomProcessingService
{
    public function processBomUpload(BomHeader $bomHeader, array $parsedData)
    {
        try {
            Log::info('Starting BOM processing for: ' . $bomHeader->bom_number);
            
            DB::transaction(function () use ($bomHeader, $parsedData) {
                $lineNumber = 1;
                foreach ($parsedData['data'] as $row) {
                    if (empty($row['part_no']) && empty($row['part_discription'])) {
                        continue;
                    }
                    
                    $qty = floatval($row['qty'] ?? 0);
                    if ($qty == 0 && strpos(strtoupper($row['part_discription'] ?? ''), 'ASSEMBLY') !== false) {
                        continue;
                    }
                    
                    BomLineItem::create([
                        'bom_header_id' => $bomHeader->id,
                        'item_code' => substr($row['part_no'] ?? null, 0, 500),
                        'part_number' => substr($row['part_no'] ?? null, 0, 500),
                        'description' => substr($row['part_discription'] ?? '', 0, 65535),
                        'part_code' => substr($row['part_code'] ?? null, 0, 500),
                        'material_specification' => substr($row['material_specification'] ?? null, 0, 500),
                        'size_of_material' => substr($row['size_of_material'] ?? null, 0, 500),
                        'quantity' => $qty,
                        'uom' => $row['unit'] ?? 'NOS',
                        'purchase_technical_spec_no' => substr($row['purchase_technical_specification_no'] ?? null, 0, 500),
                        'stock_verification' => substr($row['stock_verification_yes/no'] ?? null, 0, 50),
                        'remarks' => substr($row['remarks'] ?? null, 0, 65535),
                        'allocated_to' => 'Production',
                        'line_number' => $lineNumber++,
                        'raw_data' => $row
                    ]);
                }
            });
            
            $lineItemsCount = $bomHeader->lineItems()->count();
            Log::info("Created {$lineItemsCount} line items for BOM: " . $bomHeader->bom_number);
            
            // Update processed_at timestamp
            $bomHeader->update(['processed_at' => now()]);
            
            // Dispatch inventory check job (this will handle everything else)
            ProcessInventoryCheck::dispatch($bomHeader);
            
            return true;
        } catch (\Exception $e) {
            Log::error('BOM Processing Failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            $bomHeader->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            throw $e;
        }
    }

public function performInventoryCheck(BomHeader $bomHeader)
{
    Log::info('Performing inventory check for BOM: ' . $bomHeader->bom_number);
    
    // Check if inventory check was already performed
    if ($bomHeader->processed_at && $bomHeader->status === 'completed') {
        Log::info('Inventory check already completed for BOM: ' . $bomHeader->bom_number);
        return $bomHeader->lineItems;
    }
    
    $items = $bomHeader->lineItems;
    $inStockCount = 0;
    $partialCount = 0;
    $outOfStockCount = 0;
    
    foreach ($items as $item) {
        // Skip if already processed
        if ($item->inventory_status && $item->available_quantity !== null) {
            Log::info('Item already processed: ' . $item->item_code);
            continue;
        }
        
        $inventory = Inventory::where('item_code', $item->item_code)
            ->orWhere('item_code', $item->part_number)
            ->first();
        
        if ($inventory) {
            $availableQty = $inventory->available_quantity;
            $requiredQty = $item->quantity;
            
            if ($availableQty >= $requiredQty) {
                $item->update([
                    'inventory_status' => 'in_stock',
                    'available_quantity' => $availableQty,
                    'shortfall_quantity' => 0
                ]);
                $inStockCount++;
                
                // Allocate in-stock items immediately
                $this->allocateSingleItem($item, $inventory);
                
            } elseif ($availableQty > 0) {
                $shortfall = $requiredQty - $availableQty;
                $item->update([
                    'inventory_status' => 'partial',
                    'available_quantity' => $availableQty,
                    'shortfall_quantity' => $shortfall
                ]);
                $partialCount++;
                
                // Allocate what's available
                $this->allocateSingleItem($item, $inventory, $availableQty);
                
            } else {
                $item->update([
                    'inventory_status' => 'out_of_stock',
                    'available_quantity' => 0,
                    'shortfall_quantity' => $requiredQty
                ]);
                $outOfStockCount++;
            }
        } else {
            $item->update([
                'inventory_status' => 'out_of_stock',
                'available_quantity' => 0,
                'shortfall_quantity' => $item->quantity
            ]);
            $outOfStockCount++;
        }
    }
    
    Log::info("Inventory check completed for BOM {$bomHeader->bom_number}: In Stock: {$inStockCount}, Partial: {$partialCount}, Out of Stock: {$outOfStockCount}");
    
    // Create purchase intents for shortfalls (only if not already created)
    $this->createPurchaseIntents($bomHeader);
    
    return $items;
}
    
    protected function allocateSingleItem($item, $inventory, $customQuantity = null)
    {
        $allocateQty = $customQuantity ?? $item->quantity;
        $allocateQty = min($allocateQty, $inventory->available_quantity);
        
        if ($allocateQty > 0 && $item->allocated_quantity < $item->quantity) {
            // Reserve the stock
            $inventory->increment('quantity_reserved', $allocateQty);
            
            $allocationNumber = 'MA-' . date('Ymd') . '-' . Str::upper(Str::random(8));
            
            MaterialAllocation::create([
                'allocation_number' => $allocationNumber,
                'bom_header_id' => $item->bom_header_id,
                'bom_line_item_id' => $item->id,
                'item_code' => $item->item_code,
                'item_description' => $item->description,
                'allocated_quantity' => $allocateQty,
                'original_required_quantity' => $item->quantity,
                'allocated_to' => $item->allocated_to ?? 'Production',
                'allocated_by' => 'System',
                'allocated_at' => now()
            ]);
            
            $item->increment('allocated_quantity', $allocateQty);
            
            Log::info("Allocated {$allocateQty} units of {$item->item_code} for BOM");
        }
    }

  public function createPurchaseIntents(BomHeader $bomHeader)
{
    Log::info('Creating purchase intents for BOM: ' . $bomHeader->bom_number);
    
    // Check if intents were already created for this BOM
    $existingIntents = PurchaseIntent::where('bom_header_id', $bomHeader->id)->count();
    if ($existingIntents > 0) {
        Log::info('Purchase intents already exist for BOM: ' . $bomHeader->bom_number . ' (' . $existingIntents . ' intents)');
        return [];
    }
    
    $itemsToPurchase = $bomHeader->lineItems()
        ->whereIn('inventory_status', ['partial', 'out_of_stock'])
        ->where('shortfall_quantity', '>', 0)
        ->get();
    
    if ($itemsToPurchase->isEmpty()) {
        Log::info('No purchase intents needed for BOM: ' . $bomHeader->bom_number);
        return [];
    }
    
    $batchNumber = 'INT-' . date('Ymd') . '-' . Str::upper(Str::random(6));
    $intents = [];
    
    foreach ($itemsToPurchase as $item) {
        // Double-check if intent already exists for this line item
        $existingIntent = PurchaseIntent::where('bom_line_item_id', $item->id)->first();
        if ($existingIntent) {
            Log::info('Intent already exists for line item: ' . $item->id);
            continue;
        }
        
        $intentNumber = 'PI-' . date('Ymd') . '-' . Str::upper(Str::random(8));
        
        try {
            $intent = PurchaseIntent::create([
                'intent_number' => $intentNumber,
                'batch_number' => $batchNumber,
                'bom_header_id' => $bomHeader->id,
                'bom_line_item_id' => $item->id,
                'item_code' => $item->item_code,
                'item_description' => $item->description,
                'material_specification' => $item->material_specification,
                'required_quantity' => $item->quantity,
                'available_quantity' => $item->available_quantity,
                'shortfall_quantity' => $item->shortfall_quantity,
                'priority' => 'normal',
                'status' => 'pending',
                'raised_by' => $bomHeader->uploaded_by
            ]);
            
            $intents[] = $intent;
            Log::info('Created purchase intent: ' . $intentNumber . ' for item: ' . $item->item_code);
            
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry error
                Log::warning('Duplicate intent prevented for line item: ' . $item->id);
                continue;
            }
            throw $e;
        }
    }
    
    Log::info('Created ' . count($intents) . ' new purchase intents for BOM: ' . $bomHeader->bom_number);
    
    // Dispatch notification job only if new intents were created
    if (!empty($intents)) {
        CreatePurchaseIntents::dispatch($bomHeader);
    }
    
    return $intents;
}

    public function allocateMaterials(BomHeader $bomHeader)
    {
        // This method is kept for compatibility but allocation is now done in performInventoryCheck
        Log::info('Additional material allocation check for BOM: ' . $bomHeader->bom_number);
        return [];
    }
    public function checkStockAndDecide(BomHeader $bomHeader)
{
    Log::info('Checking stock and deciding for BOM: ' . $bomHeader->bom_number);
    
    $items = $bomHeader->lineItems;
    $results = [];
    
    foreach ($items as $item) {
        $inventory = Inventory::where('item_code', $item->item_code)->first();
        
        if ($inventory) {
            $availableQty = $inventory->available_quantity;
            $requiredQty = $item->quantity;
            
            if ($availableQty >= $requiredQty) {
                // Stock available - allocate directly
                $results[$item->item_code] = [
                    'status' => 'available',
                    'available_quantity' => $availableQty,
                    'action' => 'allocate'
                ];
                Log::info("Stock available for {$item->item_code}: {$availableQty} >= {$requiredQty}");
                
            } else {
                // Stock insufficient - need purchase
                $shortfall = $requiredQty - $availableQty;
                $results[$item->item_code] = [
                    'status' => 'shortfall',
                    'shortfall_quantity' => $shortfall,
                    'available_quantity' => $availableQty,
                    'action' => 'purchase'
                ];
                Log::info("Stock insufficient for {$item->item_code}: Need {$requiredQty}, Have {$availableQty}, Shortfall {$shortfall}");
            }
        } else {
            // No stock - need purchase
            $results[$item->item_code] = [
                'status' => 'no_stock',
                'shortfall_quantity' => $requiredQty,
                'available_quantity' => 0,
                'action' => 'purchase'
            ];
            Log::info("No stock found for {$item->item_code}");
        }
    }
    
    return $results;
}
}