@extends('layouts.app')

@section('content')
<h2>Create New Inventory Item</h2>

<form action="{{ route('inventory.store') }}" method="POST">
    @csrf
    <form action="{{ route('inventory.store') }}" method="POST">
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mb-3">
        <label>Item Code</label>
        <input name="item_code" class="form-control" required> <!-- Corrected name attribute -->
    </div>
    <div class="mb-3">
        <label>Item Name</label>
        <input name="item_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Item Group</label>
        <select name="item_group" class="form-control" required>
            @foreach($itemGroups as $group)
                <option value="{{ $group['name'] }}">{{ $group['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Stock UOM</label>
        <select name="stock_uom" class="form-control" required>
            @foreach($uoms as $uom)
                <option value="{{ $uom['name'] }}">{{ $uom['name'] }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Save</button>
</form>
@endsection
