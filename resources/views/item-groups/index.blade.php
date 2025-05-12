@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Item Groups</h1>
    <a href="{{ route('item-groups.create') }}" class="btn btn-primary mb-3">Add Item Group</a>

    <ul class="list-group">
        @foreach ($itemGroups as $group)
            <tr>
                <td>{{ $group['item_group_name'] ?? $group['name'] ?? 'N/A' }}</td>
                <!-- Add more columns if needed -->
            </tr>
        @endforeach

    </ul>
</div>
@endsection
