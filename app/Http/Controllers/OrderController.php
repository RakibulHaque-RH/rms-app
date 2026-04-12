<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Inventory;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'user', 'items.menu']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
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
        $menus = Menu::with(['menuIngredients.inventory'])->where('is_available', true)->get();
        $unconfiguredMenuCount = $menus->filter(fn($menu) => $menu->menuIngredients->isEmpty())->count();
        $menuItems = $menus->groupBy('category');
        $menuMeta = $menus->mapWithKeys(function ($menu) {
            return [$menu->id => [
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'ingredients' => $menu->menuIngredients->map(function ($ingredient) {
                    return [
                        'inventory_id' => $ingredient->inventory_id,
                        'item_name' => $ingredient->inventory->item_name ?? 'Unknown Item',
                        'unit' => $ingredient->inventory->unit ?? 'unit',
                        'qty_per_dish' => (float) $ingredient->quantity_per_dish,
                    ];
                })->values(),
            ]];
        });

        return view('orders.create', compact('tables', 'menuItems', 'menuMeta', 'unconfiguredMenuCount'));
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

        [$hasStock, $stockMessage] = $this->hasSufficientInventoryForItems($request->items);
        if (!$hasStock) {
            return back()->withErrors(['items' => $stockMessage])->withInput();
        }

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
        $previousStatus = $order->status;

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (in_array($request->status, ['served', 'completed']) && !$order->inventory_deducted_at) {
            [$hasStock, $stockMessage] = $this->hasSufficientInventoryForItems(
                $order->items->map(fn($item) => [
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                ])->toArray()
            );

            if (!$hasStock) {
                return back()->withErrors(['status' => $stockMessage]);
            }
        }

        $order->update(['status' => $request->status, 'notes' => $request->notes]);

        if (in_array($request->status, ['served', 'completed']) && $previousStatus !== $request->status) {
            $this->deductInventoryForOrder($order->fresh('items.menu'));
        }

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
        $currentPaid = (float) ($order->paid_amount ?? 0);
        $totalAmount = (float) $order->total_amount;
        $remainingDue = max($totalAmount - $currentPaid, 0);

        if ($remainingDue <= 0) {
            return redirect()->route('orders.edit', $order)->with('success', 'This order is already fully paid.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,bkash,rocket,card',
            'paid_amount' => 'required|numeric|min:0.01|max:' . $remainingDue,
            'payment_reference' => 'nullable|string|max:100',
        ]);

        if (in_array($request->payment_method, ['bkash', 'rocket', 'card']) && blank($request->payment_reference)) {
            return back()->withErrors([
                'payment_reference' => 'Reference is required for bKash, Rocket, and card payments.',
            ]);
        }

        $paymentAmount = (float) $request->paid_amount;
        $newPaidAmount = min($currentPaid + $paymentAmount, $totalAmount);
        $paymentStatus = $newPaidAmount >= $totalAmount ? 'paid' : 'partial';

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.edit', $order)->with('success', 'Payment recorded successfully.');
    }

    public function startOnlinePayment(Order $order)
    {
        $currentPaid = (float) ($order->paid_amount ?? 0);
        $totalAmount = (float) $order->total_amount;
        $remainingDue = max($totalAmount - $currentPaid, 0);

        if ($remainingDue <= 0) {
            return redirect()->route('orders.edit', $order)->with('success', 'This order is already fully paid.');
        }

        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $baseUrl = rtrim((string) config('services.sslcommerz.base_url'), '/');

        if (blank($storeId) || blank($storePassword) || blank($baseUrl)) {
            return redirect()->route('orders.edit', $order)->withErrors([
                'payment_gateway' => 'SSLCommerz is not configured. Add store credentials in .env first.',
            ]);
        }

        $tranId = 'RMS-' . $order->id . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);

        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'sslcommerz',
            'transaction_id' => $tranId,
            'amount' => $remainingDue,
            'status' => 'initiated',
        ]);

        $payload = [
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'total_amount' => number_format($remainingDue, 2, '.', ''),
            'currency' => 'BDT',
            'tran_id' => $tranId,
            'success_url' => route('payments.sslcommerz.success'),
            'fail_url' => route('payments.sslcommerz.fail'),
            'cancel_url' => route('payments.sslcommerz.cancel'),
            'ipn_url' => route('payments.sslcommerz.ipn'),
            'shipping_method' => 'NO',
            'product_name' => 'Order ' . $order->order_number,
            'product_category' => 'Restaurant',
            'product_profile' => 'general',
            'cus_name' => $order->user->name ?? 'Guest',
            'cus_email' => $order->user->email ?? 'guest@example.com',
            'cus_add1' => 'Restaurant POS',
            'cus_city' => 'Dhaka',
            'cus_postcode' => '1200',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->user->phone ?? '01700000000',
            'value_a' => (string) $order->id,
            'value_b' => (string) auth()->id(),
            'value_c' => $order->order_number,
        ];

        try {
            $response = Http::asForm()->timeout(30)->post($baseUrl . '/gwprocess/v4/api.php', $payload);
            $result = $response->json();

            $transaction->update(['gateway_response' => $result]);

            if ($response->failed() || blank($result['GatewayPageURL'] ?? null)) {
                $transaction->update(['status' => 'failed']);
                Log::warning('SSLCommerz initiate failed', ['order_id' => $order->id, 'response' => $result]);

                return redirect()->route('orders.edit', $order)->withErrors([
                    'payment_gateway' => 'Unable to start online payment now. Please try again.',
                ]);
            }

            return redirect()->away($result['GatewayPageURL']);
        } catch (\Throwable $e) {
            $transaction->update(['status' => 'failed']);
            Log::error('SSLCommerz initiate exception', ['order_id' => $order->id, 'error' => $e->getMessage()]);

            return redirect()->route('orders.edit', $order)->withErrors([
                'payment_gateway' => 'Payment gateway connection failed. Please try again.',
            ]);
        }
    }

    public function sslCommerzSuccess(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();
        $order = $transaction?->order;

        if (!$order && $request->filled('value_a')) {
            $order = Order::find($request->input('value_a'));
        }

        if (!$order) {
            return redirect()->route('orders.index')->withErrors(['payment_gateway' => 'Payment order not found.']);
        }

        if (!$transaction) {
            $transaction = PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway' => 'sslcommerz',
                'transaction_id' => $tranId,
                'amount' => (float) $request->input('amount', 0),
                'status' => 'pending',
            ]);
        }

        $validated = $this->verifySslCommerzPayment((string) $request->input('val_id'));
        $validatedStatus = strtoupper((string) ($validated['status'] ?? ''));
        $isValid = in_array($validatedStatus, ['VALID', 'VALIDATED']);

        if (!$isValid) {
            $transaction->update([
                'status' => 'failed',
                'gateway_response' => ['callback' => $request->all(), 'validation' => $validated],
            ]);

            return redirect()->route('orders.edit', $order)->withErrors([
                'payment_gateway' => 'Gateway verification failed. Payment was not recorded.',
            ]);
        }

        $paidAmount = (float) ($validated['amount'] ?? $validated['store_amount'] ?? $transaction->amount);
        $paymentMethod = $this->resolvePaymentMethodFromCardType((string) ($validated['card_type'] ?? ''));

        $currentPaid = (float) ($order->paid_amount ?? 0);
        $totalAmount = (float) $order->total_amount;
        $newPaidAmount = min($currentPaid + $paidAmount, $totalAmount);
        $paymentStatus = $newPaidAmount >= $totalAmount ? 'paid' : 'partial';

        $order->update([
            'payment_method' => $paymentMethod,
            'payment_reference' => $tranId,
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
            'paid_at' => now(),
        ]);

        $transaction->update([
            'amount' => $paidAmount,
            'status' => 'success',
            'payment_method' => $paymentMethod,
            'gateway_response' => ['callback' => $request->all(), 'validation' => $validated],
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.edit', $order)->with('success', 'Online payment completed successfully.');
    }

    public function sslCommerzFail(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();
        $order = $transaction?->order;

        if ($transaction) {
            $transaction->update([
                'status' => 'failed',
                'gateway_response' => ['callback' => $request->all()],
            ]);
        }

        if ($order) {
            return redirect()->route('orders.edit', $order)->withErrors([
                'payment_gateway' => 'Online payment failed. Please try again.',
            ]);
        }

        return redirect()->route('orders.index')->withErrors(['payment_gateway' => 'Online payment failed.']);
    }

    public function sslCommerzCancel(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();
        $order = $transaction?->order;

        if ($transaction) {
            $transaction->update([
                'status' => 'cancelled',
                'gateway_response' => ['callback' => $request->all()],
            ]);
        }

        if ($order) {
            return redirect()->route('orders.edit', $order)->withErrors([
                'payment_gateway' => 'Payment cancelled by customer.',
            ]);
        }

        return redirect()->route('orders.index')->withErrors(['payment_gateway' => 'Payment cancelled.']);
    }

    public function sslCommerzIpn(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();

        if (!$transaction) {
            return response('Transaction not found', 404);
        }

        if ($transaction->status === 'success') {
            return response('OK', 200);
        }

        $validated = $this->verifySslCommerzPayment((string) $request->input('val_id'));
        $validatedStatus = strtoupper((string) ($validated['status'] ?? ''));

        if (!in_array($validatedStatus, ['VALID', 'VALIDATED'])) {
            $transaction->update([
                'status' => 'failed',
                'gateway_response' => ['ipn' => $request->all(), 'validation' => $validated],
            ]);

            return response('INVALID', 400);
        }

        $order = $transaction->order;
        $paidAmount = (float) ($validated['amount'] ?? $validated['store_amount'] ?? $transaction->amount);
        $paymentMethod = $this->resolvePaymentMethodFromCardType((string) ($validated['card_type'] ?? ''));

        $currentPaid = (float) ($order->paid_amount ?? 0);
        $totalAmount = (float) $order->total_amount;
        $newPaidAmount = min($currentPaid + $paidAmount, $totalAmount);
        $paymentStatus = $newPaidAmount >= $totalAmount ? 'paid' : 'partial';

        $order->update([
            'payment_method' => $paymentMethod,
            'payment_reference' => $tranId,
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
            'paid_at' => now(),
        ]);

        $transaction->update([
            'amount' => $paidAmount,
            'status' => 'success',
            'payment_method' => $paymentMethod,
            'gateway_response' => ['ipn' => $request->all(), 'validation' => $validated],
            'paid_at' => now(),
        ]);

        return response('OK', 200);
    }

    public function receipt(Order $order)
    {
        $order->load(['table', 'user', 'items.menu']);
        return view('orders.receipt', compact('order'));
    }

    private function deductInventoryForOrder(Order $order): void
    {
        if ($order->inventory_deducted_at) {
            return;
        }

        $deductions = [];

        foreach ($order->items as $item) {
            $menu = Menu::with('menuIngredients.inventory')->find($item->menu_id);
            if (!$menu || $menu->menuIngredients->isEmpty()) {
                continue;
            }

            foreach ($menu->menuIngredients as $ingredient) {
                $neededQty = (float) $ingredient->quantity_per_dish * (float) $item->quantity;
                $deductions[$ingredient->inventory_id] = ($deductions[$ingredient->inventory_id] ?? 0) + $neededQty;
            }
        }

        foreach ($deductions as $inventoryId => $qty) {
            $inventory = Inventory::find($inventoryId);
            if (!$inventory) {
                continue;
            }

            $newQty = max((float) $inventory->quantity - $qty, 0);
            $inventory->update(['quantity' => $newQty]);
        }

        $order->update(['inventory_deducted_at' => now()]);
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
                    'Recipe is missing for menu item "' . $menu->name . '". Add required inventory items in Menu settings first.',
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

    private function verifySslCommerzPayment(string $valId): array
    {
        if (blank($valId)) {
            return [];
        }

        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $baseUrl = rtrim((string) config('services.sslcommerz.base_url'), '/');

        try {
            $response = Http::timeout(30)->get($baseUrl . '/validator/api/validationserverAPI.php', [
                'val_id' => $valId,
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'v' => 1,
                'format' => 'json',
            ]);

            if ($response->failed()) {
                return [];
            }

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('SSLCommerz verify exception', ['val_id' => $valId, 'error' => $e->getMessage()]);
            return [];
        }
    }

    private function resolvePaymentMethodFromCardType(string $cardType): string
    {
        $normalized = strtolower($cardType);

        if (str_contains($normalized, 'bkash')) {
            return 'bkash';
        }

        if (str_contains($normalized, 'rocket')) {
            return 'rocket';
        }

        return 'card';
    }
}
