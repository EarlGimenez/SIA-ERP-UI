@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-edit me-2"></i>Edit Item Group</h1>
        <p class="text-muted">Update this inventory category</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('item-groups.update', $group['name']) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="original_name" value="{{ $group['name'] }}">
                    
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
                    
                    <div class="mb-4">
                        <label for="item_group_name" class="form-label">Item Group Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" id="item_group_name" name="item_group_name" class="form-control" 
                                value="{{ $group['item_group_name'] ?? $group['name'] }}" required>
                        </div>
                        <div class="form-text text-warning mt-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Changing the name will create a new group and migrate all items to it
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('item-groups.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Update Group
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="mt-4">
                    <h5 class="text-danger mb-3"><i class="fas fa-skull-crossbones me-2"></i>Danger Zone</h5>
                    <form method="POST" action="{{ route('item-groups.destroy', $group['name']) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this item group?')">
                            <i class="fas fa-trash-alt me-1"></i> Delete Group
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection