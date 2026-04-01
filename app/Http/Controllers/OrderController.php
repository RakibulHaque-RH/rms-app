<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'user', 'items.menu']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $orders = $query->latest()->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->orWhere('status', 'occupied')->get();
        $menuItems = Menu::where('is_available', true)->get()->groupBy('category');
        return view('orders.create', compact('tables', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'table_id' => $request->table_id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $menuItem = Menu::find($item['menu_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $menuItem->price,
                'subtotal' => $menuItem->price * $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);

            $inventory = \App\Models\Inventory::where('item_name', $menuItem->name)->first();
            if ($inventory && $inventory->quantity >= $item['quantity']) {
                $inventory->decrement('quantity', $item['quantity']);
            }
        }

        $order->calculateTotal();
        Table::where('id', $request->table_id)->update(['status' => 'occupied']);
        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function edit(Order $order)
    {
        $order->load(['items.menu']);
        $tables = Table::all();
        $menuItems = Menu::where('is_available', true)->get()->groupBy('category');
        return view('orders.edit', compact('order', 'tables', 'menuItems'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update(['status' => $request->status, 'notes' => $request->notes]);

        if (in_array($request->status, ['completed', 'cancelled'])) {
            $active = Order::where('table_id', $order->table_id)->where('id', '!=', $order->id)
                ->whereNotIn('status', ['completed', 'cancelled'])->count();
            if ($active === 0) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
        }
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $tableId = $order->table_id;
        $order->delete();
        $active = Order::where('table_id', $tableId)->whereNotIn('status', ['completed', 'cancelled'])->count();
        if ($active === 0) {
            Table::where('id', $tableId)->update(['status' => 'available']);
        }
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }

    public function recordPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bkash,rocket,card',
            'paid_amount' => 'required|numeric|min:0.01|max:' . $order->total_amount,
            'payment_reference' => 'nullable|string|max:100',
        ]);

        if (in_array($request->payment_method, ['bkash', 'rocket', 'card']) && blank($request->payment_reference)) {
            return back()->withErrors([
                'payment_reference' => 'Reference is required for bKash, Rocket, and card payments.',
            ]);
        }

        $paidAmount = (float) $request->paid_amount;
        $paymentStatus = $paidAmount >= (float) $order->total_amount ? 'paid' : 'partial';

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'paid_amount' => $paidAmount,
            'payment_status' => $paymentStatus,
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.edit', $order)->with('success', 'Payment recorded successfully.');
    }
}
