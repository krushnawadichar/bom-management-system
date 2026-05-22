<?php

namespace App\Jobs;

use App\Models\BomHeader;
use App\Services\BomProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AllocateMaterials implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $bomHeader;

    public function __construct(BomHeader $bomHeader)
    {
        $this->bomHeader = $bomHeader;
    }

    public function handle(BomProcessingService $processingService)
    {
        try {
            Log::info('Starting material allocation for BOM: ' . $this->bomHeader->bom_number);
            
            $allocations = $processingService->allocateMaterials($this->bomHeader);
            
            Log::info('Created ' . count($allocations) . ' allocations for BOM: ' . $this->bomHeader->bom_number);
        } catch (\Exception $e) {
            Log::error('Material allocation failed for BOM ' . $this->bomHeader->bom_number . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Material allocation job failed permanently for BOM ' . $this->bomHeader->bom_number . ': ' . $exception->getMessage());
    }
}