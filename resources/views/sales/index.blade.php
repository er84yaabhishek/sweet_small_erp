@extends('layouts.admin')
@section('title', 'Sales History')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-shopping-bag"></i> Sales History</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-cash-register"></i> New Bill (POS)
    </a>
</div>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-3">
        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice No</th><th>Date</th><th>Customer</th><th>Items</th>
                        <th>Subtotal</th><th>Discount</th><th>Tax</th><th>Total</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->invoice_no }}</strong></td>
                        <td>{{ $sale->sale_date }} {{ \Carbon\Carbon::parse($sale->sale_time)->format('h:i A') }}</td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->items->count() }} items</td>
                        <td>₹{{ number_format($sale->subtotal, 2) }}</td>
                        <td>₹{{ number_format($sale->discount_amount, 2) }}</td>
                        <td>₹{{ number_format($sale->tax_amount, 2) }}</td>
                        <td><strong>₹{{ number_format($sale->total_amount, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ $sale->status == 'completed' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-print"></i> Bill
                            </a>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">No sales found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $sales->links() }}
    </div>
</div>
@endsection
