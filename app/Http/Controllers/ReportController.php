<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count();
        $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->where('status', 'completed')->count();
        $cancelledOrders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->where('status', 'cancelled')->count();

        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')->orderBy('date')->get();

        $topItems = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('menus.name')->orderByDesc('total_quantity')->take(10)->get();

        $categoryRevenue = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select('menus.category', DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('menus.category')->orderByDesc('total_revenue')->get();

        return view('reports.index', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalOrders',
            'completedOrders', 'cancelledOrders', 'dailyRevenue', 'topItems', 'categoryRevenue'
        ));
    }
}
