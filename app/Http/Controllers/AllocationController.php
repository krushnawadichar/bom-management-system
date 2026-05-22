<?php

namespace App\Http\Controllers;

use App\Models\MaterialAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialAllocation::with(['bomHeader']);
        
        if ($request->allocated_to) {
            $query->where('allocated_to', $request->allocated_to);
        }
        
        $allocations = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
        
        return view('allocations.index', compact('allocations'));
    }

    public function show($id)
    {
        $allocation = MaterialAllocation::with(['bomHeader', 'bomLineItem'])->findOrFail($id);
        return view('allocations.show', compact('allocation'));
    }

    public function acknowledge($id)
    {
        try {
            $allocation = MaterialAllocation::findOrFail($id);
            // Update allocation status or create acknowledgment record
            Log::info('Allocation acknowledged: ' . $allocation->allocation_number . ' by ' . auth()->user()->email);
            
            return response()->json(['success' => true, 'message' => 'Allocation acknowledged']);
        } catch (\Exception $e) {
            Log::error('Allocation acknowledgment failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function apiIndex(Request $request)
    {
        $allocations = MaterialAllocation::select([
            'allocation_number',
            'item_code',
            'item_description',
            'allocated_quantity',
            'allocated_to',
            'allocated_at'
        ])->paginate($request->get('per_page', 20));
        
        return response()->json($allocations);
    }
}