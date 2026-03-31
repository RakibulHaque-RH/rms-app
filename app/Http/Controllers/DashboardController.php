<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Inventory;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

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
        $mostPopularDish = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menus.name')
            ->orderByDesc('total_quantity')
            ->first();
        $recentOrders = Order::with(['table', 'user', 'items'])->latest()->take(10)->get();
        $pendingOrders = Order::whereIn('status', ['pending', 'preparing'])->with(['table', 'user'])->latest()->get();

        return view('dashboard.index', compact(
            'todayOrders', 'todayRevenue', 'totalRevenue',
            'availableTables', 'totalTables', 'occupiedTables',
            'totalMenuItems', 'lowStockItems', 'totalStaff',
            'mostPopularDish', 'recentOrders', 'pendingOrders'
        ));
    }
}
