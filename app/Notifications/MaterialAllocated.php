<?php

namespace App\Notifications;

use App\Models\MaterialAllocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaterialAllocated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $allocation;

    public function __construct(MaterialAllocation $allocation)
    {
        $this->allocation = $allocation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'material_allocated',
            'allocation_id' => $this->allocation->id,
            'allocation_number' => $this->allocation->allocation_number,
            'item_code' => $this->allocation->item_code,
            'quantity' => $this->allocation->allocated_quantity,
            'allocated_to' => $this->allocation->allocated_to,
            'message' => 'Material allocated: ' . $this->allocation->allocated_quantity . ' units of ' . $this->allocation->item_code,
            'created_at' => now()->toDateTimeString()
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}