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
            $maxEstimated = collect($data['services'])->max(fn($item) => $item['estimated_date']);

            $order = Order::query()->create([
                'user_id' => $data['user_id'],
                'order_number' => 'ORD-' . uniqid(),
                'status' => 'diterima',
                'pickup_date' => now(),
                'estimated_date' => now()->addDays($maxEstimated),
            ]);

            Log::info("Order created: ", [$order]);
    
            $totalAmount = 0;

            foreach($data['services'] as $item) {
                $service = Service::query()->findOrFail($item['service_id']);

                $quantity = $item['quantity'];
                $estimated = $item['estimated_date'];
                $amount = $quantity * $service->price;
                $totalAmount += $amount;

                $order->orderDetails()->create([
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'estimated_date' => now()->addDays($estimated),
                    'price' => $service->price,
                    'amount' => $amount,
                ]);
            }

            $order->update([
                'quantity' => collect($data['services'])->sum('quantity'),
                'total_amount' => $totalAmount
            ]);

            return $order;
        });
    }

    public function updateOrder(Order $order, $status) {
        return DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);

            if ($status === 'lunas') {
                $order->orderDetails()->update(['payment_status' => 'paid']);
            }

            Event::dispatch(new OrderStatusUpdated($order));
        });
    }

    public function getOrdersByMonth($selectedMonth)
    {
        return Order::with('user', 'orderDetails')
            ->whereRaw("TO_CHAR(created_at, 'YYYY-MM') = ?", [$selectedMonth])
            ->get();
    }

    public function getAvailableMonth()
    {
        return fn() => Order::selectRaw("DISTINCT TO_CHAR(created_at, 'YYYY-MM') AS month")
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

    public function getTotalAmount($selectedMonth)
    {
        return DB::table('orders')
            ->whereRaw("TO_CHAR(orders.created_at, 'YYYY-MM') = ?", [$selectedMonth])
            ->where('orders.status', 'lunas')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->sum('order_details.amount');
    }

    public function getMonthlyTransactions()
    {
        $startDate = now()->subMonth(5)->startOfMonth();

        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 'lunas')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->selectRaw("TO_CHAR(orders.created_at, 'Mon YYYY') AS month, SUM(order_details.amount) AS total")
            ->groupBy('month')
            ->orderByRaw("MIN(orders.created_at)")
            ->get();
    }
}
