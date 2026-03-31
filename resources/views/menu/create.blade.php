@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('subtitle', 'Create a new menu item')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header"><i class="fas fa-plus-circle me-2"></i>New Menu Item</div>
            <div class="card-body">
                <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Item Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category *</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}" list="categories" required>
                            <datalist id="categories">
                                @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
                                <option value="Appetizers"><option value="Main Course"><option value="Desserts">
                                <option value="Beverages"><option value="Salads"><option value="Soups">
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price ($) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_available" class="form-check-input" id="avail" value="1" checked>
                                <label class="form-check-label" for="avail">Available for ordering</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Item</button>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
