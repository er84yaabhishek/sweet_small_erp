<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweet Shop ERP - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100%;
            background: #2c3e50;
            color: white;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .brand {
            padding: 20px;
            text-align: center;
            background: #1a2a3a;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid #3e5a6f;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #f8b400;
            color: #2c3e50;
        }
        .sidebar .nav-link i { width: 25px; }
        .content {
            margin-left: 260px;
            padding: 20px;
            transition: all 0.3s;
        }
        .navbar-top {
            background: white;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-large {
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .brand span, .sidebar .nav-link span { display: none; }
            .sidebar .nav-link i { margin: 0 auto; }
            .content { margin-left: 70px; }
        }
        .badge-low-stock { background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; }
        .badge-in-stock { background: #28a745; color: white; padding: 5px 10px; border-radius: 5px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            🍬 <span>Sweet Shop ERP</span>
        </div>
        <nav class="nav flex-column">
            <a href="{{ route('reports.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            <a href="{{ route('categories.index') }}" class="nav-link"><i class="fas fa-tags"></i> <span>Categories</span></a>
            <a href="{{ route('units.index') }}" class="nav-link"><i class="fas fa-ruler"></i> <span>Units</span></a>
            <a href="{{ route('items.index') }}" class="nav-link"><i class="fas fa-box"></i> <span>Items</span></a>
            <a href="{{ route('suppliers.index') }}" class="nav-link"><i class="fas fa-truck"></i> <span>Suppliers</span></a>
            <a href="{{ route('purchases.index') }}" class="nav-link"><i class="fas fa-shopping-cart"></i> <span>Purchases</span></a>
            <a href="{{ route('purchase-returns.index') }}" class="nav-link"><i class="fas fa-undo"></i> <span>Purchase Returns</span></a>
            <a href="{{ route('recipes.index') }}" class="nav-link"><i class="fas fa-book"></i> <span>Recipes</span></a>
            <a href="{{ route('production-logs.index') }}" class="nav-link"><i class="fas fa-industry"></i> <span>Production</span></a>
            <a href="{{ route('sales.index') }}" class="nav-link"><i class="fas fa-shopping-bag"></i> <span>Sales</span></a>
            <a href="{{ route('sales.create') }}" class="nav-link"><i class="fas fa-cash-register"></i> <span>POS</span></a>
            <a href="{{ route('sale-returns.index') }}" class="nav-link"><i class="fas fa-undo-alt"></i> <span>Sale Returns</span></a>
            <a href="{{ route('expenses.index') }}" class="nav-link"><i class="fas fa-money-bill"></i> <span>Expenses</span></a>
            <a href="{{ route('reports.stock') }}" class="nav-link"><i class="fas fa-chart-line"></i> <span>Reports</span></a>
        </nav>
    </div>

    <div class="content">
        <div class="navbar-top">
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center gap-3">
                <select id="languageSwitcher" class="form-select form-select-sm" style="width: 100px;">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>हिंदी</option>
                </select>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> 
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#toggleSidebar').click(function() {
            $('.sidebar').toggleClass('active');
        });
        $('#languageSwitcher').change(function() {
            window.location.href = '/lang/' + $(this).val();
        });
    </script>
    @stack('scripts')
</body>
</html>