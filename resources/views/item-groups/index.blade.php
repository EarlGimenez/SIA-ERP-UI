@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1><i class="fas fa-tags me-2"></i>Item Groups</h1>
        <p class="text-muted">Organize your inventory with custom categories</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('item-groups.create') }}" class="btn btn-coffee">
            <i class="fas fa-plus-circle me-1"></i> Add Item Group
        </a>
    </div>
</div>

@if (session('error') && session('protected_group'))
<div class="alert alert-danger alert-dismissible fade show mb-4">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <form action="{{ route('item-groups.forceDestroy', session('protected_group')) }}" method="POST" class="d-inline ms-3">
        @csrf
        @method('POST')
        <button type="submit" class="btn btn-sm btn-danger" 
            onclick="return confirm('WARNING: This will PERMANENTLY DELETE ALL ITEMS in this group. Continue?')">
            <i class="fas fa-skull-crossbones me-1"></i> Force Delete Anyway
        </button>
    </form>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        @if(count($itemGroups) > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Group Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemGroups as $group)
                            <tr>
                                <td class="align-middle">
                                    <strong>{{ $group['item_group_name'] ?? $group['name'] ?? 'N/A' }}</strong>
                                    @if(($group['show_in_website'] ?? false))
                                    <span class="badge bg-info ms-2">Public</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('item-groups.edit', $group['name']) }}" class="btn btn-sm btn-cream">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('item-groups.destroy', $group['name']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item group?')">
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
                <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                <p>No item groups found. Let's create some categories!</p>
                <a href="{{ route('item-groups.create') }}" class="btn btn-coffee mt-2">
                    <i class="fas fa-plus-circle me-1"></i> Add First Group
                </a>
            </div>
        @endif
    </div>
</div>
@endsection