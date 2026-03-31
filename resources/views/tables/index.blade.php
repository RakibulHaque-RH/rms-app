@extends('layouts.app')
@section('title', 'Tables')
@section('subtitle', 'Manage restaurant tables and seating')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-3">
        <div class="d-flex align-items-center gap-2" style="font-size:13px"><span style="width:12px;height:12px;border-radius:50%;background:#10b981;display:inline-block"></span> Available</div>
        <div class="d-flex align-items-center gap-2" style="font-size:13px"><span style="width:12px;height:12px;border-radius:50%;background:#ef4444;display:inline-block"></span> Occupied</div>
        <div class="d-flex align-items-center gap-2" style="font-size:13px"><span style="width:12px;height:12px;border-radius:50%;background:#3b82f6;display:inline-block"></span> Reserved</div>
        <div class="d-flex align-items-center gap-2" style="font-size:13px"><span style="width:12px;height:12px;border-radius:50%;background:#6b7280;display:inline-block"></span> Maintenance</div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTableModal"><i class="fas fa-plus me-2"></i>Add Table</button>
</div>

<div class="row g-4">
    @forelse($tables as $table)
    <div class="col-xl-2 col-lg-3 col-md-4 col-6 fade-in">
        <div class="table-card {{ $table->status }}">
            <i class="fas fa-chair fa-2x mb-2" style="opacity:.4"></i>
            <div class="table-num">{{ $table->table_number }}</div>
            <div class="table-cap"><i class="fas fa-users me-1"></i>{{ $table->capacity }} seats</div>
            @if($table->location)<div class="table-cap mt-1"><i class="fas fa-map-marker-alt me-1"></i>{{ ucfirst($table->location) }}</div>@endif
            <div class="mt-2"><span class="status-badge {{ $table->status }}" style="font-size:11px">{{ ucfirst($table->status) }}</span></div>
            @if($table->activeOrder)
                <div class="mt-2" style="font-size:11px;color:var(--text-muted)">{{ $table->activeOrder->order_number }}</div>
            @endif
            <div class="mt-3 d-flex gap-1 justify-content-center">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTable{{ $table->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                <form action="{{ route('tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-chair fa-3x mb-3 text-muted"></i>
        <p class="text-muted">No tables added yet</p>
    </div>
    @endforelse
</div>

@foreach($tables as $table)
<!-- Edit Modal -->
<div class="modal fade" id="editTable{{ $table->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content" style="border-radius:16px;border:none">
    <div class="modal-header"><h6 class="modal-title fw-bold">Edit Table {{ $table->table_number }}</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <form action="{{ route('tables.update', $table) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label fw-semibold">Table Number</label><input type="text" name="table_number" class="form-control" value="{{ $table->table_number }}" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Capacity</label><input type="number" name="capacity" class="form-control" value="{{ $table->capacity }}" min="1" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    @foreach(['available','occupied','reserved','maintenance'] as $s)
                        <option value="{{ $s }}" {{ $table->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control" value="{{ $table->location }}" placeholder="e.g. Indoor, Outdoor, VIP"></div>
            <button type="submit" class="btn btn-primary w-100">Update Table</button>
        </form>
    </div>
</div></div></div>
@endforeach

<!-- Add Table Modal -->
<div class="modal fade" id="addTableModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content" style="border-radius:16px;border:none">
    <div class="modal-header"><h6 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Add New Table</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <form action="{{ route('tables.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label class="form-label fw-semibold">Table Number *</label><input type="text" name="table_number" class="form-control" placeholder="e.g. T-01" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Capacity *</label><input type="number" name="capacity" class="form-control" min="1" value="4" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control" placeholder="Indoor, Outdoor, VIP"></div>
            <button type="submit" class="btn btn-primary w-100">Add Table</button>
        </form>
    </div>
</div></div></div>
@endsection
