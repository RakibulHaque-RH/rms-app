@extends('layouts.app')
@section('title', 'Reports')
@section('subtitle', 'Sales analytics and performance metrics')

@section('content')
<!-- Date Range Filter -->
<div class="card mb-4 fade-in">
    <div class="card-body py-3">
        <form class="d-flex gap-3 align-items-end flex-wrap" method="GET">
            <div>
                <label class="form-label fw-semibold mb-1" style="font-size:12px">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div>
                <label class="form-label fw-semibold mb-1" style="font-size:12px">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <button class="btn btn-primary"><i class="fas fa-filter me-1"></i>Apply</button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6 fade-in">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-1">
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-receipt"></i></div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-2">
        <div class="stat-card info">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $completedOrders }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 fade-in fade-in-delay-3">
        <div class="stat-card danger">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-value">{{ $cancelledOrders }}</div>
            <div class="stat-label">Cancelled</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8 fade-in">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-chart-line me-2" style="color:var(--primary)"></i>Daily Revenue</div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Revenue -->
    <div class="col-xl-4 fade-in fade-in-delay-1">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-chart-pie me-2" style="color:var(--secondary)"></i>Revenue by Category</div>
            <div class="card-body">
                <canvas id="categoryChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Items Table -->
<div class="card fade-in fade-in-delay-2">
    <div class="card-header"><i class="fas fa-trophy me-2" style="color:var(--accent)"></i>Top Selling Items</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>#</th><th>Item</th><th>Qty Sold</th><th>Revenue</th></tr></thead>
                <tbody>
                    @forelse($topItems as $i => $item)
                    <tr>
                        <td>
                            @if($i < 3)<span class="badge" style="background:{{ ['#f59e0b','#94a3b8','#cd7f32'][$i] }};width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px">{{ $i+1 }}</span>
                            @else <span class="text-muted">{{ $i+1 }}</span>@endif
                        </td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>{{ $item->total_quantity }}</td>
                        <td><strong>${{ number_format($item->total_revenue, 2) }}</strong></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No data for this period</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Revenue Chart
const revCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyRevenue->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#6366f1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Category Chart
const catCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(catCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryRevenue->pluck('category')) !!},
        datasets: [{
            data: {!! json_encode($categoryRevenue->pluck('total_revenue')) !!},
            backgroundColor: ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } } },
        cutout: '65%'
    }
});
</script>
@endpush
@endsection
