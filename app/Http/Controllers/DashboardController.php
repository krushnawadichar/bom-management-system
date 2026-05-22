<?php

namespace App\Http\Controllers;

use App\Models\BomHeader;
use App\Models\PurchaseIntent;
use App\Models\MaterialAllocation;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Widget stats
        $totalBoms = BomHeader::count();
        $pendingIntents = PurchaseIntent::where('status', 'pending')->count();
        $totalAllocations = MaterialAllocation::count();
        $completedBoms = BomHeader::where('status', 'completed')->count();
        
        // Recent BOMs
        $recentBoms = BomHeader::with('project', 'uploader')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Intents
        $recentIntents = PurchaseIntent::with('bomHeader')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Inventory Summary
        $lowStockItems = Inventory::whereRaw('quantity_on_hand - quantity_reserved <= minimum_stock_level')->count();
        
        // Chart data - BOM uploads by month
        $bomUploadsByMonth = BomHeader::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // Chart data - Intent status distribution
        $intentStatusDistribution = PurchaseIntent::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('dashboard.index', compact(
            'totalBoms',
            'pendingIntents',
            'totalAllocations',
            'completedBoms',
            'recentBoms',
            'recentIntents',
            'lowStockItems',
            'bomUploadsByMonth',
            'intentStatusDistribution'
        ));
    }
}