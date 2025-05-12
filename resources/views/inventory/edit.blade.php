@extends('layouts.app')

@section('content')
<h2>Edit Inventory Item</h2>

<form action="{{ route('inventory.update', $item['name']) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Item Name</label>
        <input name="item_name" class="form-control" value="{{ $item['item_name'] }}" required>
    </div>
    <div class="mb-3">
        <label>Item Group</label>
        <select name="item_group" class="form-control" required>
            @foreach($itemGroups as $group)
                <option value="{{ $group['name'] }}" {{ $item['item_group'] === $group['name'] ? 'selected' : '' }}>
                    {{ $group['name'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Stock UOM</label>
        <select name="stock_uom" class="form-control" required>
            @foreach($uoms as $uom)
                <option value="{{ $uom['name'] }}" {{ $item['stock_uom'] === $uom['name'] ? 'selected' : '' }}>
                    {{ $uom['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Optional Fields Toggle -->
    <div class="mb-3">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleOptionalFields()">
            Toggle Optional Fields
        </button>
    </div>

    <!-- Optional Fields (Hidden by Default) -->
    <div id="optionalFields" style="display: none;">
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $item['description'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label>Item Image</label>
            <input type="file" name="image" class="form-control">
            @if(isset($item['image']))
                <div class="mt-2">
                    <img src="{{ config('services.erpnext.url') }}{{ $item['image'] }}" alt="Current Image" style="max-height: 100px;">
                </div>
            @endif
        </div>
    </div>

    <button class="btn btn-primary">Update</button>
</form>

<script>
    function toggleOptionalFields() {
        const div = document.getElementById('optionalFields');
        div.style.display = div.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection