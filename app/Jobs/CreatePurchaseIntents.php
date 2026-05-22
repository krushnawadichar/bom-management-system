<?php

namespace App\Jobs;

use App\Models\BomHeader;
use App\Services\BomProcessingService;
use App\Notifications\PurchaseIntentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CreatePurchaseIntents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $bomHeader;
    public $timeout = 3600;

    public function __construct(BomHeader $bomHeader)
    {
        $this->bomHeader = $bomHeader;
    }

    public function handle(BomProcessingService $processingService)
    {
        try {
            Log::info('Creating purchase intents for BOM: ' . $this->bomHeader->bom_number);
            
            $intents = $processingService->createPurchaseIntents($this->bomHeader);
            
            if (!empty($intents)) {
                // Get users with purchase-dept role
                $purchaseUsers = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'purchase-dept');
                })->get();
                
                if ($purchaseUsers->count() > 0) {
                    // Send notification via database only (no email)
                    foreach ($purchaseUsers as $user) {
                        $user->notify(new PurchaseIntentCreated($this->bomHeader, $intents));
                    }
                    Log::info('Notifications sent to ' . $purchaseUsers->count() . ' purchase department users');
                } else {
                    Log::warning('No purchase department users found to notify');
                }
                
                Log::info('Created ' . count($intents) . ' purchase intents for BOM: ' . $this->bomHeader->bom_number);
            } else {
                Log::info('No purchase intents needed for BOM: ' . $this->bomHeader->bom_number);
            }
        } catch (\Exception $e) {
            Log::error('Purchase intent creation failed for BOM ' . $this->bomHeader->bom_number . ': ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Purchase intent creation failed permanently for BOM ' . $this->bomHeader->bom_number . ': ' . $exception->getMessage());
    }
}