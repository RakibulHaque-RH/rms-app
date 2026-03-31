@extends('layouts.app')
@section('title', 'Create Order')
@section('subtitle', 'Add a new order')

@section('content')
<form method="POST" action="{{ route('orders.store') }}" id="orderForm">
    @csrf
    <div class="row g-4">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chair me-2"></i>Order Details</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Table *</label>
                            <select name="table_id" class="form-select" required>
                                <option value="">Select a table</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                        {{ $table->table_number }} ({{ $table->capacity }} seats) — {{ ucfirst($table->status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="Special instructions..." value="{{ old('notes') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="card">
                <div class="card-header"><i class="fas fa-utensils me-2"></i>Menu Items</div>
                <div class="card-body">
                    @foreach($menuItems as $category => $items)
                    <h6 class="fw-bold text-uppercase mb-3 mt-2" style="color:var(--primary);font-size:13px;letter-spacing:1px">
                        <i class="fas fa-tag me-1"></i>{{ $category }}
                    </h6>
                    <div class="row g-3 mb-4">
                        @foreach($items as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 menu-select-card" style="cursor:pointer;transition:all .2s" onclick="toggleItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }}, this)">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold" style="font-size:14px">{{ $item->name }}</div>
                                        <div style="color:var(--text-muted);font-size:12px">{{ Str::limit($item->description, 40) }}</div>
                                    </div>
                                    <span class="badge bg-primary">${{ number_format($item->price, 2) }}</span>
                                </div>
                                <div class="mt-2 qty-control d-none">
                                    <div class="input-group input-group-sm" onclick="event.stopPropagation()">
                                        <button type="button" class="btn btn-outline-secondary" onclick="changeQty({{ $item->id }}, -1)">−</button>
                                        <input type="number" class="form-control text-center" id="qty-{{ $item->id }}" min="1" value="1" style="max-width:60px" onchange="updateOrderSummary()">
                                        <button type="button" class="btn btn-outline-secondary" onclick="changeQty({{ $item->id }}, 1)">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card" style="position:sticky;top:100px">
                <div class="card-header"><i class="fas fa-shopping-cart me-2"></i>Order Summary</div>
                <div class="card-body">
                    <div id="orderSummary">
                        <div class="text-center py-4 text-muted" id="emptyMsg">
                            <i class="fas fa-cart-plus fa-2x mb-2"></i>
                            <p style="font-size:13px">Click menu items to add</p>
                        </div>
                    </div>
                    <div id="orderItems"></div>
                    <hr class="d-none" id="totalDivider">
                    <div class="d-flex justify-content-between fw-bold d-none" id="totalRow">
                        <span>Total</span><span id="totalAmount">$0.00</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3" id="submitBtn" disabled>
                        <i class="fas fa-check me-2"></i>Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="hiddenInputs"></div>
</form>

@push('styles')
<style>
.menu-select-card.selected { border-color:var(--primary)!important; background:rgba(99,102,241,.04); box-shadow:0 0 0 2px rgba(99,102,241,.15); }
</style>
@endpush

@push('scripts')
<script>
let selectedItems = {};
function toggleItem(id, name, price, el) {
    if (selectedItems[id]) {
        delete selectedItems[id];
        el.classList.remove('selected');
        el.querySelector('.qty-control').classList.add('d-none');
    } else {
        selectedItems[id] = { name, price, qty: 1 };
        el.classList.add('selected');
        el.querySelector('.qty-control').classList.remove('d-none');
    }
    updateOrderSummary();
}
function changeQty(id, delta) {
    if (!selectedItems[id]) return;
    let input = document.getElementById('qty-' + id);
    let newQty = Math.max(1, parseInt(input.value) + delta);
    input.value = newQty;
    selectedItems[id].qty = newQty;
    updateOrderSummary();
}
function updateOrderSummary() {
    let keys = Object.keys(selectedItems);
    let html = '', total = 0, hidden = '';
    document.getElementById('emptyMsg').classList.toggle('d-none', keys.length > 0);
    document.getElementById('totalDivider').classList.toggle('d-none', keys.length === 0);
    document.getElementById('totalRow').classList.toggle('d-none', keys.length === 0);
    document.getElementById('submitBtn').disabled = keys.length === 0;

    keys.forEach((id, i) => {
        let item = selectedItems[id];
        item.qty = parseInt(document.getElementById('qty-' + id).value) || 1;
        let sub = item.qty * item.price;
        total += sub;
        html += `<div class="d-flex justify-content-between align-items-center mb-2" style="font-size:13px">
            <div><strong>${item.name}</strong> × ${item.qty}</div><div>$${sub.toFixed(2)}</div></div>`;
        hidden += `<input type="hidden" name="items[${i}][menu_id]" value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
    });

    document.getElementById('orderItems').innerHTML = html;
    document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
    document.getElementById('hiddenInputs').innerHTML = hidden;
}
</script>
@endpush
@endsection
