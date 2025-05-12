<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --coffee-dark: #5D4037;
            --coffee-medium: #8D6E63;
            --coffee-light: #A1887F;
            --cream: #EFEBE9;
            --cream-light: #F5F5F5;
            --accent: #D7CCC8;
        }
        
        body {
            background-color: var(--cream);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--coffee-dark);
        }
        
        .navbar {
            background-color: var(--coffee-dark);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--cream) !important;
        }
        
        .nav-link {
            color: var(--cream) !important;
            margin: 0 5px;
            transition: all 0.3s;
            border-radius: 20px;
            padding: 8px 15px !important;
        }
        
        .nav-link:hover {
            background-color: var(--coffee-light);
        }
        
        .nav-link.active {
            background-color: var(--coffee-medium);
        }
        
        .container {
            background-color: var(--cream-light);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 20px;
            margin-top: 20px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--coffee-dark);
        }
        
        .btn-coffee {
            background-color: var(--coffee-medium);
            color: var(--cream);
            border: none;
        }
        
        .btn-coffee:hover {
            background-color: var(--coffee-dark);
            color: var(--cream);
        }
        
        .btn-cream {
            background-color: var(--cream);
            color: var(--coffee-dark);
            border: 1px solid var(--coffee-light);
        }
        
        .btn-cream:hover {
            background-color: var(--accent);
            color: var(--coffee-dark);
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--coffee-light);
            color: var(--cream);
        }
        
        .list-group-item {
            border-left: none;
            border-right: none;
            border-color: var(--accent);
        }
        
        .form-control:focus {
            border-color: var(--coffee-light);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
        }
        
        /* Ensure proper padding in cards */
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            padding: 1.25rem 1.5rem;
            background-color: transparent;
            border-top: 1px solid var(--accent);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('inventory.index') }}">
                <i class="fas fa-boxes me-2"></i>Inventory System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                            <i class="fas fa-boxes me-1"></i> Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('inventory.create') }}" class="nav-link {{ request()->routeIs('inventory.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle me-1"></i> Add Item
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('uoms.index') }}" class="nav-link {{ request()->routeIs('uoms.*') ? 'active' : '' }}">
                            <i class="fas fa-balance-scale me-1"></i> UOMs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('item-groups.index') }}" class="nav-link {{ request()->routeIs('item-groups.*') ? 'active' : '' }}">
                            <i class="fas fa-tags me-1"></i> Item Groups
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <footer class="text-center py-4 mt-4 text-muted">
        <small>&copy; {{ date('Y') }} Inventory Management System</small>
    </footer>
</body>
</html>