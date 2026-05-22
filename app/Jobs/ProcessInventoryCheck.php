<?php

namespace App\Jobs;

use App\Models\BomHeader;
use App\Services\BomProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessInventoryCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $bomHeader;
    public $timeout = 3600;
    public $tries = 3;

    public function __construct(BomHeader $bomHeader)
    {
        $this->bomHeader = $bomHeader;
    }

public function handle(BomProcessingService $processingService)
{
    try {
        Log::info('Starting inventory check job for BOM: ' . $this->bomHeader->bom_number);
        
        // Check if already processed
        if ($this->bomHeader->status === 'completed') {
            Log::info('BOM already processed, skipping: ' . $this->bomHeader->bom_number);
            return;
        }
        
        if ($this->bomHeader->status === 'processing') {
            Log::info('BOM already being processed, skipping: ' . $this->bomHeader->bom_number);
            return;
        }
        
        // Mark as processing to prevent duplicate processing
        $this->bomHeader->update(['status' => 'processing']);
        
        // Perform inventory check (this will also create intents and allocations)
        $processingService->performInventoryCheck($this->bomHeader);
        
        // Update status to completed
        $this->bomHeader->update(['status' => 'completed']);
        
        Log::info('Completed inventory check job for BOM: ' . $this->bomHeader->bom_number);
        
    } catch (\Exception $e) {
        Log::error('Inventory check job failed for BOM ' . $this->bomHeader->bom_number . ': ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        $this->bomHeader->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        throw $e;
    }
}
    public function failed(\Throwable $exception)
    {
        Log::error('Inventory check job failed permanently for BOM ' . $this->bomHeader->bom_number . ': ' . $exception->getMessage());
        $this->bomHeader->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
    }
}