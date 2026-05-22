<?php

namespace App\Notifications;

use App\Models\BomHeader;
use App\Models\PurchaseIntent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PurchaseIntentCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bomHeader;
    protected $intents;

    public function __construct(BomHeader $bomHeader, array $intents)
    {
        $this->bomHeader = $bomHeader;
        $this->intents = $intents;
    }

    public function via($notifiable)
    {
        // Use only database channel to avoid mail configuration issues
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'purchase_intent_created',
            'bom_id' => $this->bomHeader->id,
            'bom_number' => $this->bomHeader->bom_number,
            'batch_number' => $this->intents[0]->batch_number ?? null,
            'total_intents' => count($this->intents),
            'message' => 'New purchase intents created for BOM ' . $this->bomHeader->bom_number,
            'created_at' => now()->toDateTimeString()
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}