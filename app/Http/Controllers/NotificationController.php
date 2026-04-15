<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = collect();

        $pendingOrders = Order::whereIn('status', ['pending', 'preparing'])->count();
        if ($pendingOrders > 0) {
            $notifications->push([
                'type' => 'warning',
                'title' => 'Pending Kitchen Orders',
                'message' => $pendingOrders . ' orders are waiting in pending or preparing status.',
                'link' => route('orders.index', ['status' => 'pending']),
                'link_text' => 'View Orders',
            ]);
        }

        $readyOrders = Order::where('status', 'ready')->count();
        if ($readyOrders > 0) {
            $notifications->push([
                'type' => 'info',
                'title' => 'Orders Ready to Serve',
                'message' => $readyOrders . ' orders are marked ready for service.',
                'link' => route('orders.index', ['status' => 'ready']),
                'link_text' => 'Open Orders',
            ]);
        }

        if (Schema::hasColumn('orders', 'payment_status')) {
            $unpaidOrders = Order::whereIn('payment_status', ['unpaid', 'partial'])->count();
            if ($unpaidOrders > 0) {
                $notifications->push([
                    'type' => 'danger',
                    'title' => 'Outstanding Payments',
                    'message' => $unpaidOrders . ' orders still need full payment.',
                    'link' => route('orders.index', ['payment_status' => 'unpaid']),
                    'link_text' => 'Check Payments',
                ]);
            }
        } else {
            $notifications->push([
                'type' => 'warning',
                'title' => 'Payment Module Not Ready',
                'message' => 'Run migrations to enable payment-based notifications.',
                'link' => route('dashboard'),
                'link_text' => 'Go to Dashboard',
            ]);
        }

        if (in_array($user->role ?? '', ['admin', 'manager'])) {
            if (Schema::hasColumn('orders', 'is_customer_approved') && Schema::hasColumn('orders', 'order_source')) {
                $awaitingApproval = Order::where('order_source', 'customer')
                    ->where('is_customer_approved', false)
                    ->count();

                if ($awaitingApproval > 0) {
                    $notifications->push([
                        'type' => 'warning',
                        'title' => 'Customer Orders Awaiting Approval',
                        'message' => $awaitingApproval . ' customer order(s) are waiting for manager/admin approval.',
                        'link' => route('orders.index'),
                        'link_text' => 'Review Orders',
                    ]);
                }
            }

            $lowStockItems = Inventory::whereColumn('quantity', '<=', 'min_quantity')->count();
            if ($lowStockItems > 0) {
                $notifications->push([
                    'type' => 'danger',
                    'title' => 'Low Stock Alert',
                    'message' => $lowStockItems . ' inventory items are low in stock.',
                    'link' => route('inventory.index', ['low_stock' => 1]),
                    'link_text' => 'View Inventory',
                ]);
            }
        }

        if ($notifications->isEmpty()) {
            $notifications->push([
                'type' => 'success',
                'title' => 'All Clear',
                'message' => 'No important notifications right now.',
                'link' => route('dashboard'),
                'link_text' => 'Back to Dashboard',
            ]);
        }

        return view('notifications.index', [
            'notifications' => $notifications,
            'notificationCount' => $notifications->where('type', '!=', 'success')->count(),
        ]);
    }
}
