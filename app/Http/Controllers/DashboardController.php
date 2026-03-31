<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Inventory;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $availableTables = Table::where('status', 'available')->count();
        $totalTables = Table::count();
        $occupiedTables = Table::where('status', 'occupied')->count();
        $totalMenuItems = Menu::count();
        $lowStockItems = Inventory::whereColumn('quantity', '<=', 'min_quantity')->count();
        $totalStaff = User::where('role', '!=', 'admin')->count();
        $recentOrders = Order::with(['table', 'user', 'items'])->latest()->take(10)->get();
        $pendingOrders = Order::whereIn('status', ['pending', 'preparing'])->with(['table', 'user'])->latest()->get();

        return view('dashboard.index', compact(
            'todayOrders', 'todayRevenue', 'totalRevenue',
            'availableTables', 'totalTables', 'occupiedTables',
            'totalMenuItems', 'lowStockItems', 'totalStaff',
            'recentOrders', 'pendingOrders'
        ));
    }
}
