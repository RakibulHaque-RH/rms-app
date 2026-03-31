@extends('layouts.app')
@section('title', 'Kitchen Display')
@section('subtitle', 'Live ticket view for chefs')

@push('styles')
<style>
    .kitchen-ticket { border-radius: 12px; border-top: 5px solid; background: var(--card-bg); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .kitchen-ticket.pending { border-color: var(--warning); }
    .kitchen-ticket.preparing { border-color: var(--primary); }
    .ticket-header { padding: 16px; border-bottom: 2px dashed var(--border); background: rgba(0,0,0,0.02); }
    .ticket-body { padding: 16px; min-height: 150px; }
    .ticket-footer { padding: 16px; background: rgba(0,0,0,0.02); display: flex; gap: 10px; }
    .item-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: 500; font-size: 15px; }
    .item-notes { font-size: 12px; color: var(--danger); font-style: italic; margin-top: -5px; margin-bottom: 10px; }
    .qty-badge { background: var(--dark); color: #fff; padding: 2px 8px; border-radius: 6px; font-weight: bold; }
</style>
@endpush

@section('content')
<!-- Auto-refresh meta tag -->
<meta http-equiv="refresh" content="30">

<div class="row g-4 flex-nowrap overflow-auto pb-3" style="min-height: 70vh;">
    @forelse($orders as $order)
    <div class="col-12 col-md-6 col-lg-4 col-xl-3" style="min-width: 320px;">
        <div class="kitchen-ticket {{ $order->status }} h-100 d-flex flex-column fade-in">
            <div class="ticket-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">#{{ substr($order->order_number, -4) }}</h5>
                    <div class="text-muted" style="font-size: 12px;"><i class="fas fa-clock me-1"></i>{{ $order->created_at->format('H:i') }} ({{ $order->created_at->diffForHumans(null, true, true) }})</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-dark fw-bold fs-6">{{ $order->table->table_number ?? 'Takeaway' }}</span>
                </div>
            </div>
            
            <div class="ticket-body flex-grow-1">
                @foreach($order->items as $item)
                <div class="item-row">
                    <span>
                        <span class="qty-badge me-2">{{ $item->quantity }}x</span> 
                        {{ $item->menu->name ?? 'Unknown Item' }}
                    </span>
                </div>
                @if($item->notes)
                    <div class="item-notes"><i class="fas fa-exclamation-circle me-1"></i>{{ $item->notes }}</div>
                @endif
                @endforeach
            </div>

            <div class="ticket-footer">
                @if($order->status === 'pending')
                    <form action="{{ route('kitchen.update', $order) }}" method="POST" class="w-100">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="preparing">
                        <button class="btn btn-warning w-100 fw-bold text-dark"><i class="fas fa-fire me-2"></i>Start Cooking</button>
                    </form>
                @elseif($order->status === 'preparing')
                    <form action="{{ route('kitchen.update', $order) }}" method="POST" class="w-100">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ready">
                        <button class="btn btn-success w-100 fw-bold"><i class="fas fa-check-circle me-2"></i>Order Ready</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-mug-hot fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
        <h3 class="text-muted fw-bold">No active tickets</h3>
        <p class="text-muted">Kitchen is clear!</p>
    </div>
    @endforelse
</div>
@endsection
