<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BroadcastOrderStatusUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event)
    {
        Log::info('Broadcasting OrderStatusUpdated', [
            'order_id' => $event->order->id,
            'status' => $event->order->status,
        ]);
    }

    public function failed(OrderStatusUpdated $event, $exception)
    {
        Log::error('Broadcast failed', ['exception' => $exception]);
    }
}
