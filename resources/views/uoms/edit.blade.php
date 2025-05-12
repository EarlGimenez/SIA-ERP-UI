@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-edit me-2"></i>Edit Unit of Measure</h1>
        <p class="text-muted">Update this measurement unit</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('uoms.update', $uom['name']) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="original_name" value="{{ $uom['name'] }}">
                    
                    <div class="mb-4">
                        <label for="uom_name" class="form-label">UOM Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" id="uom_name" name="uom_name" class="form-control" 
                                value="{{ $uom['name'] }}" required>
                        </div>
                        <div class="form-text text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Changing the name will create a new UOM and migrate all items to it
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="must_be_whole_number" 
                                name="must_be_whole_number" value="1" 
                                {{ ($uom['must_be_whole_number'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="must_be_whole_number">
                                Must be whole number
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('uoms.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>

                    <hr class="my-4">

                    <div class="mt-4">
                        <h5 class="text-danger mb-3"><i class="fas fa-skull-crossbones me-2"></i>Danger Zone</h5>
                        <form method="POST" action="{{ route('uoms.destroy', $uom['name']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Are you sure you want to delete this Unit of Measure?')">
                                <i class="fas fa-trash-alt me-1"></i> Delete Group
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection