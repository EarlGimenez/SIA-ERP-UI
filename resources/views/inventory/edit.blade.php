@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-edit me-2"></i>Edit Inventory Item</h1>
        <p class="text-muted">Update details for "{{ $item['item_name'] }}"</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body p-4">
                <form action="{{ route('inventory.update', $item['name']) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="item_code" class="form-label">Item Code</label>
                            <input type="text" id="item_code" class="form-control bg-light" 
                                value="{{ $item['item_code'] }}" readonly disabled>
                            <div class="form-text">Item code cannot be changed</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="item_name" class="form-label">Item Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" id="item_name" name="item_name" class="form-control" 
                                    value="{{ $item['item_name'] }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="item_group" class="form-label">Item Group</label>
                            <select id="item_group" name="item_group" class="form-select" required>
                                @foreach($itemGroups as $group)
                                    <option value="{{ $group['name'] }}" {{ $item['item_group'] === $group['name'] ? 'selected' : '' }}>
                                        {{ $group['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="stock_uom" class="form-label">Stock UOM</label>
                            <select id="stock_uom" name="stock_uom" class="form-select" required>
                                @foreach($uoms as $uom)
                                    <option value="{{ $uom['name'] }}" {{ $item['stock_uom'] === $uom['name'] ? 'selected' : '' }}>
                                        {{ $uom['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="description" class="form-label mb-0">Description</label>
                            <button type="button" class="btn btn-sm btn-cream" 
                                data-bs-toggle="collapse" data-bs-target="#optionalFields">
                                <i class="fas fa-chevron-down me-1"></i> Toggle Optional Fields
                            </button>
                        </div>
                    </div>
                    
                    <div id="optionalFields" class="collapse show">
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3">{{ $item['description'] ?? '' }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Item Image</label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    
                                    @if(isset($item['image']))
                                        <div class="mt-3 d-flex align-items-center">
                                            <div class="me-3">
                                                <img src="{{ config('services.erpnext.url') }}{{ $item['image'] }}" 
                                                    alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                            <div>
                                                <p class="mb-1">Current image</p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                                    <label class="form-check-label" for="remove_image">Remove image</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('inventory.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-coffee-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Item Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Item Code</small>
                    <strong>{{ $item['item_code'] }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Created On</small>
                    <strong>{{ isset($item['creation']) ? date('M d, Y', strtotime($item['creation'])) : 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Last Modified</small>
                    <strong>{{ isset($item['modified']) ? date('M d, Y', strtotime($item['modified'])) : 'N/A' }}</strong>
                </div>
            </div>
        </div>
        
        <div class="card bg-light border-0">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Tips</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Keep item names consistent</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Add detailed descriptions for clarity</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Use high-quality images</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle optional fields
        const toggleBtn = document.querySelector('[data-bs-target="#optionalFields"]');
        const icon = toggleBtn.querySelector('i');
        
        toggleBtn.addEventListener('click', function() {
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        });
    });
</script>
@endsection