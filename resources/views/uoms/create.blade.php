@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="fas fa-plus-circle me-2"></i>Add Unit of Measure</h1>
        <p class="text-muted">Create a new measurement unit for your inventory</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('uoms.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="uom_name" class="form-label">UOM Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" id="uom_name" name="uom_name" class="form-control" 
                                placeholder="e.g. Kilogram, Liter, Piece" required>
                        </div>
                        <div class="form-text">Enter a unique, descriptive name for this unit of measure</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="must_be_whole_number" 
                                name="must_be_whole_number" value="1">
                            <label class="form-check-label" for="must_be_whole_number">
                                Must be whole number
                            </label>
                        </div>
                        <div class="form-text">Enable if this UOM cannot be fractional (e.g. Pieces, Units)</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('uoms.index') }}" class="btn btn-cream me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="fas fa-save me-1"></i> Save UOM
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection