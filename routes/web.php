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
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerOrderController;
use App\Models\Menu;

Route::get('/', function () {
    $menus = Menu::with(['menuIngredients.inventory'])->available()->orderBy('category')->orderBy('name')->get();
    $featuredItems = $menus->take(8);
    $menuByCategory = $featuredItems->groupBy('category');
    $orderMenuItems = $menus->groupBy('category');
    $menuMeta = $menus->mapWithKeys(function ($menu) {
        return [$menu->id => [
            'name' => $menu->name,
            'price' => (float) $menu->price,
            'ingredients' => $menu->menuIngredients->map(fn($ingredient) => [
                'inventory_id' => $ingredient->inventory_id,
            ])->values(),
        ]];
    });

    return view('welcome', compact('featuredItems', 'menuByCategory', 'orderMenuItems', 'menuMeta'));
})->name('website.home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.store');
    Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.store');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/account', [CustomerAuthController::class, 'account'])->name('customer.account');
    Route::post('/customer/orders', [CustomerOrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('customer.orders');
});

// Protected Routes
Route::middleware(['auth', 'role:manager,waiter,chef,cashier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/approve-customer', [OrderController::class, 'approveCustomerOrder'])
        ->middleware('role:manager,admin')
        ->name('orders.approve-customer');
    Route::patch('/orders/{order}/payment', [OrderController::class, 'recordPayment'])->name('orders.payment');
    Route::post('/orders/{order}/payment/online', [OrderController::class, 'startOnlinePayment'])->name('orders.payment.online');
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

Route::match(['get', 'post'], '/payments/sslcommerz/success', [OrderController::class, 'sslCommerzSuccess'])->name('payments.sslcommerz.success');
Route::match(['get', 'post'], '/payments/sslcommerz/fail', [OrderController::class, 'sslCommerzFail'])->name('payments.sslcommerz.fail');
Route::match(['get', 'post'], '/payments/sslcommerz/cancel', [OrderController::class, 'sslCommerzCancel'])->name('payments.sslcommerz.cancel');
Route::post('/payments/sslcommerz/ipn', [OrderController::class, 'sslCommerzIpn'])->name('payments.sslcommerz.ipn');
