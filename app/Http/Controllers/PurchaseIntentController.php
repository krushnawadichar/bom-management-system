<?php

namespace App\Http\Controllers;

use App\Models\PurchaseIntent;
use App\Models\BomHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseIntentController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseIntent::with(['bomHeader', 'raiser', 'acknowledger']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->batch_number) {
            $query->where('batch_number', $request->batch_number);
        }
        
        if ($request->count_only) {
            return response()->json(['count' => $query->count()]);
        }
        
        $intents = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20));
        
        $batches = PurchaseIntent::select('batch_number')->distinct()->get();
        
        return view('purchase-intents.index', compact('intents', 'batches'));
    }

    public function show($id)
    {
        $intent = PurchaseIntent::with(['bomHeader', 'bomLineItem', 'raiser', 'acknowledger'])->findOrFail($id);
        return view('purchase-intents.show', compact('intent'));
    }

    public function acknowledge($id)
    {
        try {
            $intent = PurchaseIntent::findOrFail($id);
            $intent->update([
                'status' => 'acknowledged',
                'acknowledged_by' => auth()->id(),
                'acknowledged_at' => now()
            ]);
            
            Log::info('Purchase intent acknowledged: ' . $intent->intent_number . ' by ' . auth()->user()->email);
            
            return response()->json(['success' => true, 'message' => 'Intent acknowledged successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to acknowledge intent: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function markPoRaised(Request $request, $id)
    {
        $request->validate([
            'po_reference' => 'required|string'
        ]);
        
        try {
            $intent = PurchaseIntent::findOrFail($id);
            $intent->update([
                'status' => 'po_raised',
                'po_reference' => $request->po_reference
            ]);
            
            Log::info('PO Raised for intent: ' . $intent->intent_number . ' PO: ' . $request->po_reference);
            
            return response()->json(['success' => true, 'message' => 'PO marked as raised successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to mark PO raised: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function batchAcknowledge(Request $request)
    {
        $request->validate([
            'batch_number' => 'required|string'
        ]);
        
        try {
            $count = PurchaseIntent::where('batch_number', $request->batch_number)
                ->where('status', 'pending')
                ->update([
                    'status' => 'acknowledged',
                    'acknowledged_by' => auth()->id(),
                    'acknowledged_at' => now()
                ]);
            
            Log::info('Batch acknowledged: ' . $request->batch_number . ' - ' . $count . ' intents');
            
            return redirect()->back()->with('success', $count . ' intents acknowledged successfully');
        } catch (\Exception $e) {
            Log::error('Failed to acknowledge batch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to acknowledge batch: ' . $e->getMessage());
        }
    }
}