@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-plus-circle me-2"></i>Add Item Group</h1>
        <p class="text-muted">Create a new category for organizing your inventory</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('item-groups.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="item_group_name" class="form-label">Item Group Name *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" id="item_group_name" name="item_group_name" class="form-control" 
                                value="{{ old('item_group_name') }}" required minlength="2" maxlength="140">
                        </div>
                        <div class="form-text">Enter a descriptive name (2-140 characters)</div>
                        @error('item_group_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_group" name="is_group" value="1">
                            <label class="form-check-label" for="is_group">
                                This is a parent group (can contain other groups)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_in_website" name="show_in_website" value="1">
                            <label class="form-check-label" for="show_in_website">
                                Show this group on website
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('item-groups.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Create Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection