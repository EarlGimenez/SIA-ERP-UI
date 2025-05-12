@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1><i class="fas fa-boxes me-2"></i>Inventory Items</h1>
        <p class="text-muted">Manage your inventory items</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('inventory.create') }}" class="btn btn-coffee">
            <i class="fas fa-plus-circle me-1"></i> Add New Item
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text bg-light">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search items...">
        </div>
    </div>
    <div class="col-md-auto ms-auto">
        <select id="groupFilter" class="form-select">
            <option value="">All Groups</option>
            <!-- Add group options dynamically -->
        </select>
    </div>
</div>

@if(count($items) > 0)
    <div class="row">
        @foreach($items as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 inventory-card">
                <div class="card-img-container position-relative">
                    @if(isset($item['image']))
                        <img src="{{ config('services.erpnext.url') }}{{ $item['image'] }}" class="card-img-top" 
                            alt="{{ $item['item_name'] }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-coffee-dark">{{ $item['item_group'] }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h5 class="card-title">{{ $item['item_name'] }}</h5>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-light text-dark me-2">{{ $item['item_code'] }}</span>
                        <span class="badge bg-coffee-light">{{ $item['stock_uom'] }}</span>
                    </div>
                    
                    @if(isset($item['description']) && !empty($item['description']))
                    <div class="mb-3">
                        <button class="btn btn-sm btn-cream w-100" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#desc-{{ $loop->index }}">
                            <i class="fas fa-info-circle me-1"></i> Description
                        </button>
                        <div class="collapse mt-2" id="desc-{{ $loop->index }}">
                            <div class="card card-body bg-light border-0">
                                {{ $item['description'] ?? 'No description available' }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white p-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inventory.edit', $item['name']) }}" class="btn btn-sm btn-coffee">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <form action="{{ route('inventory.destroy', $item['name']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to delete this item?')">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-boxes fa-3x mb-3 text-muted"></i>
        <h3>Your inventory is empty</h3>
        <p class="text-muted">Start adding items to manage your inventory</p>
        <a href="{{ route('inventory.create') }}" class="btn btn-coffee mt-2">
            <i class="fas fa-plus-circle me-1"></i> Add First Item
        </a>
    </div>
@endif
@endsection