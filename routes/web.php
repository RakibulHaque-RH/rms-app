<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/payment', [OrderController::class, 'recordPayment'])->name('orders.payment');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::resource('menu', MenuController::class);

    // Kitchen Display
    Route::get('/kitchen', [\App\Http\Controllers\KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/{order}', [\App\Http\Controllers\KitchenController::class, 'updateStatus'])->name('kitchen.update');

    Route::resource('tables', TableController::class)->except(['create', 'show', 'edit']);
    Route::patch('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.status');

    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('inventory', InventoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('staff', StaffController::class)->except(['create', 'show', 'edit']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
