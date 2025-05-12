@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1><i class="fas fa-balance-scale me-2"></i>Units of Measure</h1>
        <p class="text-muted">Manage measurement units for your inventory items</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('uoms.create') }}" class="btn btn-coffee">
            <i class="fas fa-plus-circle me-1"></i> Add UOM
        </a>
    </div>
</div>

@if (session('error') && session('protected_uom'))
<div class="alert alert-danger alert-dismissible fade show mb-4">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <form action="{{ route('uoms.forceDestroy', session('protected_uom')) }}" method="POST" class="d-inline ms-3">
        @csrf
        @method('POST')
        <button type="submit" class="btn btn-sm btn-danger" 
            onclick="return confirm('WARNING: This will PERMANENTLY DELETE ALL ITEMS using this UOM. Continue?')">
            <i class="fas fa-skull-crossbones me-1"></i> Force Delete Anyway
        </button>
    </form>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        @if(count($uoms) > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uoms as $uom)
                            <tr>
                                <td class="align-middle">
                                    <strong>{{ $uom['name'] }}</strong>
                                    @if(($uom['must_be_whole_number'] ?? false))
                                    <span class="badge bg-secondary ms-2">Whole Numbers</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if(($uom['must_be_whole_number'] ?? false))
                                        <span class="badge bg-secondary">Whole Number</span>
                                    @else
                                        <span class="badge bg-light text-dark">Decimal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('uoms.edit', $uom['name']) }}" class="btn btn-sm btn-cream">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('uoms.destroy', $uom['name']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this unit of measure?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-coffee fa-3x mb-3 text-muted"></i>
                <p>No units of measure found. Let's add some!</p>
                <a href="{{ route('uoms.create') }}" class="btn btn-coffee mt-2">
                    <i class="fas fa-plus-circle me-1"></i> Add First UOM
                </a>
            </div>
        @endif
    </div>
</div>
@endsection