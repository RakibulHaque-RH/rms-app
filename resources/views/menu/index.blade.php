@extends('layouts.app')
@section('title', 'Menu')
@section('subtitle', 'Manage your restaurant menu items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <div class="input-group" style="width:280px">
            <span class="input-group-text" style="background:#fff"><i class="fas fa-search text-muted"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Search menu..." value="{{ request('search') }}" style="border-left:none">
        </div>
        <select name="category" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary btn-sm">Filter</button>
    </form>
    <a href="{{ route('menu.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Item</a>
</div>

<div class="row g-4">
    @forelse($menuItems as $item)
    <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
        <div class="card h-100" style="overflow:hidden">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="height:180px;object-fit:cover">
            @else
                <div style="height:180px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-utensils fa-3x" style="color:var(--primary);opacity:.3"></i>
                </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge" style="background:rgba(99,102,241,.1);color:var(--primary);font-size:11px">{{ $item->category }}</span>
                    @if($item->is_available)
                        <span class="badge bg-success" style="font-size:10px">Available</span>
                    @else
                        <span class="badge bg-secondary" style="font-size:10px">Unavailable</span>
                    @endif
                </div>
                <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
                <p class="text-muted mb-2" style="font-size:13px;min-height:36px">{{ Str::limit($item->description, 60) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size:20px;color:var(--primary)">${{ number_format($item->price, 2) }}</span>
                    <div class="d-flex gap-1">
                        <a href="{{ route('menu.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('menu.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
        <p class="text-muted">No menu items yet</p>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">Add First Item</a>
    </div>
    @endforelse
</div>
<div class="mt-4">{{ $menuItems->withQueryString()->links() }}</div>
@endsection
