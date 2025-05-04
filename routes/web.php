<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Auth::loginUsingId(1);

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    
    Route::middleware('role_or_permission:admin')->group(function () {
        Route::resource('orders', OrderController::class);
        Route::resource('layanan', LayananController::class);
        Route::get('/laporan', [OrderController::class, 'laporan'])->name('laporan');
        Route::get('orders/{order}/{status}', [OrderController::class, 'status'])->name('orders.status');
    });

    Route::get('orders-history', [OrderController::class, 'history'])->name('orders.history');

    Route::get('pay/{order}', [PaymentController::class, 'createInvoice'])->name('orders.payment');
});

Route::post('/xendit/webhook', [PaymentController::class, 'handleWebhook'])->name('xendit.webhook');

Route::get('/download', [PdfController::class, 'download'])->name('download');
Route::get('/debug-node', function () {
    return shell_exec('which node') . "\n" . shell_exec('which npm');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
