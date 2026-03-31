@extends('layouts.app')
@section('title', 'Orders')
@section('subtitle', 'Manage all restaurant orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <select name="status" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['pending','preparing','ready','served','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <input type="date" name="date" class="form-control" style="width:auto" value="{{ request('date') }}" onchange="this.form.submit()">
    </form>
    <a href="{{ route('orders.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>New Order</a>
</div>

<div class="card fade-in">
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table mb-0">
                <thead><tr><th>Order #</th><th>Table</th><th>Waiter</th><th>Items</th><th>Status</th><th>Total</th><th>Time</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td><span class="badge bg-light text-dark">{{ $order->table->table_number ?? 'N/A' }}</span></td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->items->count() }} items</td>
                        <td><span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                        <td style="color:var(--text-muted);font-size:13px">{{ $order->created_at->format('M d, H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">
                        <i class="fas fa-receipt fa-3x mb-3" style="color:var(--text-muted)"></i>
                        <p class="text-muted">No orders found</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">Create First Order</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
@endsection
