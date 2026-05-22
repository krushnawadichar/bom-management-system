<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    protected $inventoryRepository;

    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function index(Request $request)
    {
        $query = Inventory::query();
        
        if ($request->search) {
            $query->where('item_code', 'like', "%{$request->search}%")
                  ->orWhere('item_name', 'like', "%{$request->search}%");
        }
        
        if ($request->low_stock) {
            $query->lowStock();
        }
        
        $inventory = $query->orderBy('item_code')->paginate($request->get('per_page', 20));
        
        return view('inventory.index', compact('inventory'));
    }

    public function lowStock()
    {
        $items = Inventory::lowStock()->get();
        return view('inventory.low-stock', compact('items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity_on_hand' => 'required|numeric|min:0',
            'minimum_stock_level' => 'required|numeric|min:0'
        ]);
        
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->update([
                'quantity_on_hand' => $request->quantity_on_hand,
                'minimum_stock_level' => $request->minimum_stock_level
            ]);
            
            Log::info('Inventory updated: ' . $inventory->item_code . ' by ' . auth()->user()->email);
            
            return redirect()->back()->with('success', 'Inventory updated successfully');
        } catch (\Exception $e) {
            Log::error('Inventory update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update inventory');
        }
    }

    public function apiIndex(Request $request)
    {
        $inventory = Inventory::select([
            'id',
            'item_code',
            'item_name',
            'quantity_on_hand',
            'quantity_reserved',
            'minimum_stock_level'
        ])->paginate($request->get('per_page', 20));
        
        return response()->json($inventory);
    }
}