<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        [$hasStock, $stockMessage] = $this->hasSufficientInventoryForItems($request->items);
        if (!$hasStock) {
            return back()->withErrors(['items' => $stockMessage])->withInput();
        }

        $onlineTable = Table::firstOrCreate(
            ['table_number' => 'ONLINE'],
            ['capacity' => 1, 'location' => 'Online', 'status' => 'available']
        );

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'table_id' => $onlineTable->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $request->notes,
            'order_source' => 'customer',
            'is_customer_approved' => false,
        ]);

        foreach ($request->items as $item) {
            $menuItem = Menu::findOrFail($item['menu_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $menuItem->price,
                'subtotal' => $menuItem->price * $item['quantity'],
            ]);
        }

        $order->calculateTotal();

        return redirect()->route('customer.orders')->with('success', 'Order placed. Waiting for admin/manager approval.');
    }

    public function index()
    {
        $orders = Order::with(['items.menu'])
            ->where('user_id', auth()->id())
            ->where('order_source', 'customer')
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    private function hasSufficientInventoryForItems(array $items): array
    {
        $requiredByInventory = [];
        $inventoryNames = [];

        foreach ($items as $item) {
            $menu = Menu::with('menuIngredients.inventory')->find($item['menu_id']);
            if (!$menu) {
                continue;
            }

            if ($menu->menuIngredients->isEmpty()) {
                return [
                    false,
                    'Recipe is missing for menu item "' . $menu->name . '". Please try another item.',
                ];
            }

            foreach ($menu->menuIngredients as $ingredient) {
                $neededQty = (float) $ingredient->quantity_per_dish * (float) $item['quantity'];
                $requiredByInventory[$ingredient->inventory_id] = ($requiredByInventory[$ingredient->inventory_id] ?? 0) + $neededQty;
                $inventoryNames[$ingredient->inventory_id] = $ingredient->inventory->item_name ?? 'Unknown Item';
            }
        }

        foreach ($requiredByInventory as $inventoryId => $requiredQty) {
            $inventory = Inventory::find($inventoryId);
            if (!$inventory) {
                continue;
            }

            if ((float) $inventory->quantity < $requiredQty) {
                return [
                    false,
                    'Not enough stock for ' . ($inventoryNames[$inventoryId] ?? 'an item') . '. Required: ' . $requiredQty . ', Available: ' . (float) $inventory->quantity,
                ];
            }
        }

        return [true, null];
    }
}
