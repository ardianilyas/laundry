<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Auth::loginUsingId(1);

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/{status}', [OrderController::class, 'status'])->name('orders.status');
    Route::get('orders-history', [OrderController::class, 'history'])->name('orders.history');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
