@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-plus-circle me-2"></i>Add Inventory Item</h1>
        <p class="text-muted">Create a new item in your inventory</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body p-4">
                <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if ($errors->any() || session('error'))
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                @if (session('error'))
                                    <li>{{ session('error') }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="item_code" class="form-label">Item Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text" id="item_code" name="item_code" class="form-control" 
                                    placeholder="e.g. ITM-001" required>
                            </div>
                            <div class="form-text">A unique identifier for this item</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="item_name" class="form-label">Item Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" id="item_name" name="item_name" class="form-control" 
                                    placeholder="e.g. Product Name" required>
                            </div>
                            <div class="form-text">A descriptive name for this item</div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="item_group" class="form-label">Item Group</label>
                            <select id="item_group" name="item_group" class="form-select" required>
                                <option value="" disabled selected>Select a group</option>
                                @foreach($itemGroups as $group)
                                    <option value="{{ $group['name'] }}">{{ $group['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">The category this item belongs to</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="stock_uom" class="form-label">Stock UOM</label>
                            <select id="stock_uom" name="stock_uom" class="form-select" required>
                                <option value="" disabled selected>Select a unit</option>
                                @foreach($uoms as $uom)
                                    <option value="{{ $uom['name'] }}">{{ $uom['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">How this item is measured</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="3" 
                            placeholder="Enter details about this item..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="form-label">Item Image (Optional)</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('inventory.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card bg-light border-0">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Tips</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Use clear, descriptive names</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Choose the appropriate unit of measure</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Add images to easily identify items</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Group similar items together</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection