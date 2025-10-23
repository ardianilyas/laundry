<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

// Auth::loginUsingId(1);

Route::get('/', function () {
    return redirect(route('dashboard'));
})->name('home')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    
    Route::middleware('role_or_permission:admin')->group(function () {
        Route::resource('orders', OrderController::class);
        Route::resource('layanan', LayananController::class);
        Route::get('/laporan', [OrderController::class, 'laporan'])->name('laporan');
        Route::get('orders/{order}/{status}', [OrderController::class, 'status'])->name('orders.status');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::get('orders-history', [OrderController::class, 'history'])->name('orders.history');

    Route::get("/orders/{order}", [OrderController::class, 'show'])->name('orders.show');

    Route::get('pay/{order}', [PaymentController::class, 'createInvoice'])->name('orders.payment');
});

Route::post('/xendit/webhook', [PaymentController::class, 'handleWebhook'])->name('xendit.webhook');

Route::get('/download', [PdfController::class, 'download'])->name('download');
Route::get('/debug-node', function () {
    return shell_exec('which node') . "\n" . shell_exec('which npm');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
