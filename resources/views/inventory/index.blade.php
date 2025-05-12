@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory Items</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-success mb-3">Add New Item</a>

    <div class="row">
        @foreach($items as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <!-- Item Image -->
                @if(isset($item['image']))
                    <img src="{{ config('services.erpnext.url') }}{{ $item['image'] }}" class="card-img-top" alt="{{ $item['item_name'] }}" style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <span class="text-muted">No Image Available</span>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $item['item_name'] }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $item['item_code'] }}</h6>
                    
                    <!-- Description (collapsible) -->
                    <div class="mb-2">
                        <a class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" href="#desc-{{ $loop->index }}">
                            Description
                        </a>
                        <div class="collapse mt-2" id="desc-{{ $loop->index }}">
                            <p class="card-text">{{ $item['description'] ?? 'No description available' }}</p>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Group:</strong> {{ $item['item_group'] }}
                        </li>
                        <li class="list-group-item">
                            <strong>UOM:</strong> {{ $item['stock_uom'] }}
                        </li>
                    </ul>
                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inventory.edit', $item['name']) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('inventory.destroy', $item['name']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection