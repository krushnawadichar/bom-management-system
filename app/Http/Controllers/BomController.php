<?php

namespace App\Http\Controllers;

use App\Http\Requests\BomUploadRequest;
use App\Services\BomParserService;
use App\Services\BomProcessingService;
use App\Repositories\BomRepository;
use App\Models\BomHeader;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BomController extends Controller
{
    protected $bomRepository;
    protected $parserService;
    protected $processingService;

    public function __construct(
        BomRepository $bomRepository,
        BomParserService $parserService,
        BomProcessingService $processingService
    ) {
        $this->bomRepository = $bomRepository;
        $this->parserService = $parserService;
        $this->processingService = $processingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'project_id']);
        $filters['per_page'] = $request->get('per_page', 15);
        
        $boms = $this->bomRepository->findAll($filters);
        $projects = Project::where('status', 'active')->get();
        
        return view('boms.index', compact('boms', 'projects'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        return view('boms.create', compact('projects'));
    }

public function store(BomUploadRequest $request)
{
    try {
        $file = $request->file('bom_file');
        $originalName = $file->getClientOriginalName();
        
        // Store file in public disk
        $path = $file->store('boms/' . date('Y/m/d'), 'public');
        
        Log::info('File stored at: ' . $path);
        
        // Get full path for validation
        $fullPath = Storage::disk('public')->path($path);
        
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File storage failed. Please try again.');
        }
        
        // Validate file structure
        $validation = $this->parserService->validateStructure($path);
        if (!$validation['valid']) {
            return back()->with('error', $validation['error']);
        }
        
        // Parse the file
        $parsedData = $this->parserService->parse($path, $originalName);
        
        if (!$parsedData['success']) {
            return back()->with('error', $parsedData['error']);
        }
        
        if (empty($parsedData['data'])) {
            return back()->with('error', 'No valid data found in the BOM file. Please check the file format.');
        }
        
        // Create BOM header
        $bomNumber = 'BOM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        $bomData = [
            'bom_number' => $bomNumber,
            'revision' => $request->revision ?? '00',
            'project_id' => $request->project_id,
            'file_name' => $originalName,
            'file_path' => $path,
            'original_bom_number' => $request->original_bom_number,
            'status' => 'pending',
            'uploaded_by' => auth()->id()
        ];
        
        $bomHeader = $this->bomRepository->create($bomData);
        
        // Process the BOM
        $this->processingService->processBomUpload($bomHeader, $parsedData);
        
        return redirect()->route('boms.show', $bomHeader->id)
            ->with('success', 'BOM uploaded successfully. Found ' . count($parsedData['data']) . ' line items. Processing in background.');
            
    } catch (\Exception $e) {
        Log::error('BOM Upload Failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return back()->with('error', 'Failed to upload BOM: ' . $e->getMessage());
    }
}

public function show($id)
{
    $bom = $this->bomRepository->find($id);
    // Use paginate() instead of get() for pagination
    $lineItems = $bom->lineItems()->orderBy('line_number')->paginate(25);
    
    return view('boms.show', compact('bom', 'lineItems'));
}

    public function getLineItems($id, Request $request)
    {
        $bom = $this->bomRepository->find($id);
        $lineItems = $bom->lineItems()
            ->when($request->status, function($query, $status) {
                return $query->where('inventory_status', $status);
            })
            ->paginate($request->get('per_page', 20));
        
        if ($request->ajax()) {
            return response()->json([
                'data' => $lineItems->items(),
                'total' => $lineItems->total(),
                'current_page' => $lineItems->currentPage(),
                'last_page' => $lineItems->lastPage()
            ]);
        }
        
        return view('boms.line-items', compact('bom', 'lineItems'));
    }

    public function getStatus($id)
    {
        $bom = $this->bomRepository->find($id);
        
        $stats = [
            'total_items' => $bom->lineItems()->count(),
            'in_stock' => $bom->lineItems()->where('inventory_status', 'in_stock')->count(),
            'partial' => $bom->lineItems()->where('inventory_status', 'partial')->count(),
            'out_of_stock' => $bom->lineItems()->where('inventory_status', 'out_of_stock')->count(),
            'status' => $bom->status,
            'processed_at' => $bom->processed_at
        ];
        
        return response()->json($stats);
    }

    public function getLineItemsByBomNumber($bomNumber)
    {
        $bom = $this->bomRepository->findByBomNumber($bomNumber);
        if (!$bom) {
            return response()->json(['error' => 'BOM not found'], 404);
        }
        
        $lineItems = $this->bomRepository->getLineItems($bom->id);
        return response()->json($lineItems);
    }
}