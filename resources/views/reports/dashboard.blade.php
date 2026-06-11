@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white card-hover">
            <div class="card-body">
                <h5 class="card-title">Today's Sales</h5>
                <h2 class="display-6">₹{{ number_format($todaySales, 2) }}</h2>
                <p class="mb-0">{{ $todaySalesCount }} bills</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white card-hover">
            <div class="card-body">
                <h5 class="card-title">Low Stock Items</h5>
                <h2 class="display-6">{{ $lowStockItems->count() }}</h2>
                <p class="mb-0">Need reorder</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white card-hover">
            <div class="card-body">
                <h5 class="card-title">Total Items</h5>
                <h2 class="display-6">{{ \App\Models\Item::count() }}</h2>
                <p class="mb-0">Active products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark card-hover">
            <div class="card-body">
                <h5 class="card-title">Total Suppliers</h5>
                <h2 class="display-6">{{ \App\Models\Supplier::count() }}</h2>
                <p class="mb-0">Vendors</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header">Monthly Sales Trend</div>
            <div class="card-body">
                <canvas id="salesChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">Low Stock Alert</div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($lowStockItems as $item)
                    <div class="alert alert-warning mb-2">
                        <strong>{{ $item->name }}</strong><br>
                        Stock: {{ number_format($item->currentStock(), 2) }} / Reorder: {{ $item->reorder_level }}
                    </div>
                @empty
                    <p class="text-success">All items have sufficient stock!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Recent Sales</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Invoice</th><th>Customer</th><th>Amount</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_no }}</td>
                            <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                            <td>₹{{ number_format($sale->total_amount, 2) }}</td>
                            <td>{{ $sale->sale_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Recent Purchases</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Bill No</th><th>Supplier</th><th>Amount</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($recentPurchases as $purchase)
                        <tr>
                            <td>{{ $purchase->bill_no ?? 'N/A' }}</td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>₹{{ number_format($purchase->total_amount, 2) }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')->map(function($m) { return date('M', mktime(0,0,0,$m,1)); })) !!},
            datasets: [{
                label: 'Sales (₹)',
                data: {{ $monthlySales->pluck('total') }},
                borderColor: '#f8b400',
                backgroundColor: 'rgba(248, 180, 0, 0.1)',
                tension: 0.3,
                fill: true
            }]
        }
    });
</script>
@endsection