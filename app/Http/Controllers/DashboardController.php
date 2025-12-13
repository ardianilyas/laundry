<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        
    }

    public function index() {
        $totalPendapatanHariIni = $this->orderService->getTotalAmountPaidToday();
        $totalPendapatan = $this->orderService->getTotalAmount(now()->format('Y-m'));
        $totalPendapatanTahunIni = $this->orderService->getTotalAmountThisYear();
        $totalUser = User::query()->count();
        $totalTransaksi = Order::query()->count();
        $totalTransaksiUser = Auth::user()->orders()->count();
        $monthlyTransactions = $this->orderService->getMonthlyTransactions();
        return inertia('Dashboard', compact('totalPendapatanHariIni', 'totalPendapatan', 'totalPendapatanTahunIni', 'totalUser', 'totalTransaksi', 'totalTransaksiUser', 'monthlyTransactions'));
    }
}
