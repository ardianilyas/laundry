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

    public function getOrdersByMonth($selectedMonth) {
        return Order::with('user', 'orderDetail')->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$selectedMonth])
        ->get();
    }

    public function getAvailableMonth() {
        return fn () => Order::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

    public function getTotalAmount($selectedMonth) {
        return DB::table('orders')
                    ->whereRaw('DATE_FORMAT(orders.created_at, "%Y-%m") = ?', [$selectedMonth])
                    ->where('orders.status', 'lunas')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->sum('order_details.amount');
    }

    public function getMonthlyTransactions() {
        $startDate = now()->subMonth(5)->startOfMonth();
        return DB::table('orders')
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->where('orders.status', 'lunas')
        ->whereDate('orders.created_at', '>=', $startDate)
        ->selectRaw("DATE_FORMAT(orders.created_at, '%M %Y') as month, SUM(order_details.amount) as total")
        ->groupBy('month')
        ->orderByRaw("MIN(orders.created_at)")
        ->get();
    }
}
