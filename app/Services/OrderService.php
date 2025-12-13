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
            $estimated_date = $data['estimated_date'];

            $order = Order::query()->create([
                'user_id' => $data['user_id'],
                'order_number' => 'ORD-' . uniqid(),
                'status' => 'diterima',
                'pickup_date' => now(),
                'estimated_date' => now()->addDays($estimated_date),
            ]);

            Log::info("Order created: ", [$order]);
    
            $totalAmount = 0;

            foreach($data['services'] as $item) {
                $service = Service::query()->findOrFail($item['service_id']);

                $quantity = $item['quantity'];
                $amount = $quantity * $service->price;
                $totalAmount += $amount;

                $order->orderDetails()->create([
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'estimated_date' => now()->addDays($estimated_date),
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

    private function sendOrderFinishedWhatsApp($phone, $name, $orderId)
    {
        $token = env("FONNTE_TOKEN");

        $message = "Halo *$name* 👋\n\n"
            . "Pesanan laundry Anda dengan nomor *#$orderId* "
            . "telah *SELESAI* ✅.\n\n"
            . "Silakan datang ke Ibuk Laundry untuk pengambilan.\n\n"
            . "Terima kasih 🙏";

        if (app()->environment('local')) {
            $devPhone = env('DEV_WHATSAPP', '6287877239702');
            Log::info("Development mode: redirect WhatsApp from $phone to $devPhone");
            $phone = $devPhone;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.fonnte.com/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "target" => $phone,
            "message" => $message,
        ]);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $token",
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Fonnte CURL Error: ' . curl_error($ch));
        }

        return $result;
    }

    public function updateOrder(Order $order, $status) {
        return DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);

            if ($status === 'lunas') {
                $order->orderDetails()->update(['payment_status' => 'paid']);
            }

            if ($status === 'selesai') {
                $this->sendOrderFinishedWhatsApp(
                    $order->user->phone,
                    $order->user->name,
                    $order->order_number
                );
            }

            Event::dispatch(new OrderStatusUpdated($order));
        });
    }

    public function getOrdersByMonth($selectedMonth)
    {
        return Order::with('user', 'orderDetails')
            ->whereRaw("TO_CHAR(pickup_date, 'YYYY-MM') = ?", [$selectedMonth])
            ->get();
    }

    public function getAvailableMonth()
    {
        return fn() => Order::selectRaw("DISTINCT TO_CHAR(pickup_date, 'YYYY-MM') AS month")
            ->orderBy('month', 'desc')
            ->pluck('month');
    }

    public function getTotalAmount($selectedMonth)
    {
        return DB::table('orders')
            ->whereRaw("TO_CHAR(orders.pickup_date, 'YYYY-MM') = ?", [$selectedMonth])
            ->where('orders.status', 'lunas')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->sum('order_details.amount');
    }

    public function getTotalAmountPaidToday()
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereDate('orders.updated_at', date('Y-m-d'))
            ->where('orders.status', 'lunas')
            ->sum('order_details.amount');
    }

    public function getTotalAmountThisYear()
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereYear('orders.pickup_date', date('Y'))
            ->where('orders.status', 'lunas')
            ->sum('order_details.amount');
    }

    public function getMonthlyTransactions()
    {
        $startDate = now()->subMonth(11)->startOfMonth();

        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 'lunas')
            ->whereDate('orders.pickup_date', '>=', $startDate)
            ->selectRaw("TO_CHAR(orders.pickup_date, 'Mon YYYY') AS month, SUM(order_details.amount) AS total")
            ->groupBy('month')
            ->orderByRaw("MIN(orders.pickup_date)")
            ->get();
    }
}
