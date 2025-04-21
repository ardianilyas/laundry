<?php

namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllOrders($status = null) {
        return Order::with('user')->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->latest()->paginate(10)->withQueryString();
    }

    public function storeNewOrder($data) {
        return DB::transaction(function () use ($data) {
            $order = Order::query()->create([
                'user_id' => $data['user_id'],
                'order_number' => 'ORD-' . uniqid(),
                'quantity' => $data['quantity'],
                'status' => 'diterima',
                'pickup_date' => now(),
                'estimated_date' => now()->addDays($data['estimated_date'])
            ]);

            Log::info("Order created: ", [$order]);
    
            $service = Service::where('id', $data['service_id'])->first();
            $amount = $data['quantity'] * $service['price'];
    
            $order->orderDetail()->create([
                'service_id' => $service['id'],
                'amount' => $amount,
            ]);
        });
    }

    public function updateOrder(Order $order, $status) {
        return DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);

            if ($status === 'lunas') {
                $order->orderDetail->update(['payment_status' => 'paid']);
            }

            Event::dispatch(new OrderStatusUpdated($order));
        });
    }
}
