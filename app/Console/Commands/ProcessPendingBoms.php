<?php

namespace App\Console\Commands;

use App\Models\BomHeader;
use App\Jobs\ProcessInventoryCheck;
use Illuminate\Console\Command;

class ProcessPendingBoms extends Command
{
    protected $signature = 'bom:process-pending';
    protected $description = 'Process pending BOM uploads';

    public function handle()
    {
        $pendingBoms = BomHeader::where('status', 'pending')->get();
        
        foreach ($pendingBoms as $bom) {
            $this->info('Processing BOM: ' . $bom->bom_number);
            ProcessInventoryCheck::dispatch($bom);
        }
        
        $this->info('Processed ' . $pendingBoms->count() . ' pending BOMs');
    }
}