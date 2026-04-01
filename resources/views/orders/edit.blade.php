@extends('layouts.app')
@section('title', 'Edit Order')
@section('subtitle', 'Update order ' . $order->order_number)

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-info-circle me-2"></i>Order Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted" style="font-size:12px">ORDER NUMBER</label>
                            <div class="fw-bold" style="font-size:18px">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted" style="font-size:12px">TABLE</label>
                            <div class="fw-bold">{{ $order->table->table_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted" style="font-size:12px">WAITER</label>
                            <div class="fw-bold">{{ $order->user->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted" style="font-size:12px">PAYMENT STATUS</label>
                            <div>
                                @if ($order->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status === 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-secondary">Unpaid</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header"><i class="fas fa-list me-2"></i>Order Items</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->menu->name ?? 'Deleted Item' }}</strong>
                                            @if ($item->notes)
                                                <br><small class="text-muted">{{ $item->notes }}</small>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>৳{{ number_format($item->subtotal, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f8fafc">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="fw-bold" style="font-size:18px">৳{{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="col-lg-4">
            <div class="card" style="position:sticky;top:100px">
                <div class="card-header"><i class="fas fa-sync-alt me-2"></i>Update Status</div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Status</label>
                            <div class="mb-2"><span class="status-badge {{ $order->status }}"
                                    style="font-size:14px">{{ ucfirst($order->status) }}</span></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Change To</label>
                            <select name="status" class="form-select">
                                @foreach (['pending', 'preparing', 'ready', 'served', 'completed', 'cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Update
                            Order</button>
                    </form>

                    <hr>

                    <form action="{{ route('orders.payment', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select method</option>
                                @foreach (['cash' => 'Cash', 'bkash' => 'bKash', 'rocket' => 'Rocket', 'card' => 'Card'] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('payment_method', $order->payment_method) === $value ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Paid Amount</label>
                            <input type="number" name="paid_amount" step="0.01" min="0.01"
                                max="{{ $order->total_amount }}" class="form-control"
                                value="{{ old('paid_amount', $order->payment_status === 'unpaid' ? $order->total_amount : $order->paid_amount) }}"
                                required>
                            <small class="text-muted">Order total: ৳{{ number_format($order->total_amount, 2) }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference (Txn ID)</label>
                            <input type="text" name="payment_reference" class="form-control"
                                value="{{ old('payment_reference', $order->payment_reference) }}"
                                placeholder="Required for bKash, Rocket, Card">
                        </div>

                        @if ($order->paid_at)
                            <div class="mb-3" style="font-size:13px;color:var(--text-muted)">
                                <i class="fas fa-check-circle me-1"></i>Last payment:
                                {{ $order->paid_at->format('M d, Y H:i') }}
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-credit-card me-2"></i>Record
                            Payment</button>
                    </form>

                    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-outline-secondary w-100 mt-2"
                        target="_blank">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </a>

                    <hr>
                    <div style="font-size:13px;color:var(--text-muted)">
                        <div class="mb-1"><i class="fas fa-clock me-1"></i>Created:
                            {{ $order->created_at->format('M d, Y H:i') }}</div>
                        <div><i class="fas fa-edit me-1"></i>Updated: {{ $order->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
