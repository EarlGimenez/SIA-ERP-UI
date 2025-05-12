<!DOCTYPE html>
<html>
<head>
    <title>ERP Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <nav class="mb-4">
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary">Inventory</a>
            <a href="{{ route('inventory.create') }}" class="btn btn-outline-success">Add Item</a>
            <a href="{{ route('uoms.index') }}" class="btn btn-outline-info">UOMs</a>
            <a href="{{ route('item-groups.index') }}" class="btn btn-outline-warning">Item Groups</a>
        </nav>
        @yield('content')
    </div>
</body>
</html>
