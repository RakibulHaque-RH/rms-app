<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Get all pending and preparing orders
        $orders = Order::with(['items.menu', 'table'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('kitchen.index')->with('success', "Order #{$order->order_number} marked as {$request->status}!");
    }
}
