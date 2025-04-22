<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {

    }

    public function index (Request $request) {
        $status = $request->status;

        $orders = $this->orderService->getAllOrders($status);

        return inertia('Orders/Index', compact('orders'));
    }

    public function create () {
        $users = User::all();
        $services = Service::all();
        return inertia('Orders/Create', compact('users', 'services'));
    }

    public function store (StoreOrderRequest $request) {
        $this->orderService->storeNewOrder($request->all());

        return redirect()->route('dashboard.orders.index');
    }

    public function show (Order $order) {
        $order->load(['user', 'orderDetail']);
        return inertia('Orders/Show', compact('order'));
    }

    public function status (Order $order, $status) {
        $this->orderService->updateOrder($order, $status);
        return back();
    }

    public function history () {
        $orders = Auth::user()->orders()->latest()->paginate();
        return inertia('Orders/History', compact('orders'));
    }

    public function laporan(Request $request) {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $orders = $this->orderService->getOrdersByMonth($selectedMonth);

        $totalAmount = $this->orderService->getTotalAmount($selectedMonth);

        $availableMonth = $this->orderService->getAvailableMonth();

        return inertia('Laporan/Index', compact('orders', 'availableMonth', 'selectedMonth', 'totalAmount'));
    }
}
