@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back! Here\'s what\'s happening today.')

@section('content')
<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6 fade-in">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-value">${{ number_format($todayRevenue, 2) }}</div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-1">
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-receipt"></i></div>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-label">Today's Orders</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-2">
        <div class="stat-card info">
            <div class="stat-icon"><i class="fas fa-chair"></i></div>
            <div class="stat-value">{{ $availableTables }}/{{ $totalTables }}</div>
            <div class="stat-label">Available Tables</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-3">
        <div class="stat-card {{ $lowStockItems > 0 ? 'danger' : 'warning' }}">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $lowStockItems }}</div>
            <div class="stat-label">Low Stock Alerts</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Stats -->
    <div class="col-xl-4 col-md-6 fade-in">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-bar me-2 text-primary"></i>Overview</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                    <div><div style="font-size:13px;color:var(--text-secondary)">Total Revenue</div><div style="font-size:20px;font-weight:700">${{ number_format($totalRevenue, 2) }}</div></div>
                    <div class="stat-icon" style="width:42px;height:42px;margin:0;font-size:16px;background:rgba(99,102,241,.1);color:var(--primary)"><i class="fas fa-wallet"></i></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                    <div><div style="font-size:13px;color:var(--text-secondary)">Menu Items</div><div style="font-size:20px;font-weight:700">{{ $totalMenuItems }}</div></div>
                    <div class="stat-icon" style="width:42px;height:42px;margin:0;font-size:16px;background:rgba(245,158,11,.1);color:var(--warning)"><i class="fas fa-utensils"></i></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                    <div>
                        <div style="font-size:13px;color:var(--text-secondary)">Most Popular Dish</div>
                        <div style="font-size:16px;font-weight:700">{{ $mostPopularDish?->name ?? 'No sales yet' }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $mostPopularDish?->total_quantity ?? 0 }} sold</div>
                    </div>
                    <div class="stat-icon" style="width:42px;height:42px;margin:0;font-size:16px;background:rgba(6,182,212,.1);color:var(--info)"><i class="fas fa-fire"></i></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div><div style="font-size:13px;color:var(--text-secondary)">Staff Members</div><div style="font-size:20px;font-weight:700">{{ $totalStaff }}</div></div>
                    <div class="stat-icon" style="width:42px;height:42px;margin:0;font-size:16px;background:rgba(16,185,129,.1);color:var(--success)"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-xl-8 col-md-6 fade-in fade-in-delay-1">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clock me-2" style="color:var(--warning)"></i>Active Orders</span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                @if($pendingOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Order #</th><th>Table</th><th>Waiter</th><th>Status</th><th>Amount</th></tr></thead>
                        <tbody>
                            @foreach($pendingOrders->take(6) as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->table->table_number ?? 'N/A' }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td><span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color:var(--success)"></i>
                    <p class="text-muted mb-0">No active orders right now</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row g-4">
    <div class="col-12 fade-in fade-in-delay-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2" style="color:var(--info)"></i>Recent Orders</span>
                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>New Order</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table mb-0">
                        <thead><tr><th>Order #</th><th>Table</th><th>Waiter</th><th>Items</th><th>Status</th><th>Amount</th><th>Time</th></tr></thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->table->table_number ?? 'N/A' }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ $order->items_count ?? $order->items->count() ?? 0 }} items</td>
                                <td><span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                <td style="color:var(--text-muted);font-size:13px">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">No orders yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
